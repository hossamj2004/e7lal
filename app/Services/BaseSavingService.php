<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Base Saving Service - Hossam Standard
 *
 * This abstract class provides a standardized pattern for saving data in Laravel applications.
 * It enforces a consistent flow: prepareArray() → validate() → save() → saveRelatedData() → afterSave()
 *
 * Key Features:
 * - Transaction safety with saveAndCommit()
 * - Consistent method flow for all saving operations
 * - Built-in support for related data saving
 * - Post-save hooks for additional processing
 *
 * @author Hossam Hassan
 * @version 1.0
 */
abstract class BaseSavingService
{
    /**
     * The model class name that this service operates on
     * @var string
     */
    public string $modelName;

    /**
     * Main save method - follows the standard flow
     *
     * Flow: prepareArray → validate → saveData → saveRelatedData → afterSave
     *
     * @param array $params Input parameters
     * @return mixed The saved model instance
     */
    public function save($params)
    {
        // Step 1: Prepare and transform input data
        $params = $this->prepareArray($params);

        // Step 2: Validate business rules
        $this->validate($params);

        // Step 3: Save the main model
        $model = $this->saveData($params);

        // Step 4: Save related/child data
        $this->saveRelatedData($model, $params);

        // Step 5: Post-save operations
        $this->afterSave($model, $params);

        return $model;
    }

    /**
     * Save with database transaction - RECOMMENDED for data integrity
     *
     * Wraps the save operation in a database transaction.
     * Automatically commits on success, rolls back on failure.
     *
     * @param array $params Input parameters
     * @return mixed The saved and refreshed model instance
     * @throws Exception
     */
    public function saveAndCommit($params)
    {
        DB::beginTransaction();

        try {
            $result = $this->save($params);
            DB::commit();

            // Refresh the model to get latest data with relationships
            $result->refresh();

            // Execute post-commit operations
            $this->afterCommit($result, $params);

            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Save multiple records efficiently
     *
     * @param array $params Array of parameter arrays
     * @param array $extraParams Additional parameters to merge with each record
     */
    public function saveMany($params, $extraParams = []): void
    {
        foreach ($params as $param) {
            $param = array_merge($param, $extraParams);
            $this->save($param);
        }
    }

    /**
     * Bulk save multiple records without relationships (fast method)
     *
     * @param array $params Array of parameter arrays
     * @param array $extraParams Additional parameters to merge with each record
     */
    public function storeManyFast($params, $extraParams = []): void
    {
        if (empty($params)) {
            return;
        }

        $model = new $this->modelName;
        $columns = $model->getFillable();

        if (empty($columns)) {
            // Fallback: get all columns from the table if fillable is not set
            $columns = $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
        }

        $dataToInsert = [];
        foreach ($params as $param) {
            $filtered = array_intersect_key(array_merge($param, $extraParams), array_flip($columns));
            $dataToInsert[] = $filtered;
        }

        $this->modelName::insert($dataToInsert);
    }

    /**
     * Save a single record (alias for save)
     *
     * @param array $params Input parameters
     * @return mixed
     */
    public function saveOne($params)
    {
        return $this->save($params);
    }

    /**
     * Core data saving logic - handles create vs update automatically
     *
     * @param array $params Prepared parameters
     * @return mixed The saved model instance
     */
    public function saveData($params)
    {
        // Determine if this is an update (has ID) or create (no ID)
        if (isset($params['id'])) {
            $modelName = $this->modelName;
            $modelObject = $modelName::findOrFail($params['id']);
        } else {
            $modelName = $this->modelName;
            $modelObject = new $modelName;
        }

        // Handle translatable attributes (for multi-language support)
        $translatableAttributes = property_exists($modelObject, 'translatedAttributes')
            ? $modelObject->translatedAttributes
            : [];
        $normalAttributes = [];
        $translations = [];

        // Get model casts to handle special data types
        $casts = method_exists($modelObject, 'getCasts') ? $modelObject->getCasts() : [];

        // Separate translatable and regular attributes
        foreach ($params as $key => $value) {
            if (in_array($key, $translatableAttributes) && is_array($value)) {
                $translations[$key] = $value;
                continue;
            }

            // Allow arrays for attributes that are castable to array/json/collection/object
            if (is_array($value)) {
                $castType = $casts[$key] ?? null;
                if (in_array($castType, [
                    'array',
                    'json',
                    'collection',
                    'object',
                    'encrypted:array',
                    'encrypted:collection',
                    'encrypted:json',
                ])) {
                    $normalAttributes[$key] = $value;
                }
                // Skip non-castable arrays to avoid accidental relational data filling
                continue;
            }

            $normalAttributes[$key] = $value;
        }

        // Fill regular attributes
        $modelObject->fill($normalAttributes);
        $modelObject->save();

        // Save translations if any
        foreach ($translations as $attribute => $locales) {
            foreach ($locales as $locale => $localizedValue) {
                $modelObject->translateOrNew($locale)->$attribute = $localizedValue;
            }
        }

        if (!empty($translations)) {
            $modelObject->save();
        }

        return $modelObject;
    }

    /**
     * Save many-to-many relationships
     *
     * @param array $arr Relationship data [field1 => values, field2 => value]
     */
    public function saveManyToMany($arr): void
    {
        $field1 = array_keys($arr)[0];
        $field1data = array_values($arr)[0];
        $field2 = array_keys($arr)[1];
        $field2data = array_values($arr)[1];

        // Validate that "All" (0) is not combined with other values
        if (in_array(0, $field1data)) {
            $field1data = array_diff($field1data, [0]);
            if (count($field1data) > 0)
                throw new \Exception('You must select "All" or "Any" alone or select other options.');
        }

        $newCombinations = array_unique($field1data);

        // Fetch existing from DB
        $modelName = $this->modelName;
        $existing = $modelName::where($field2, $field2data)->pluck($field1)->toArray();

        // Find combinations to delete (in DB but not in new)
        $toDelete = array_filter($existing, fn($row) => !in_array($row, $newCombinations));

        // Find combinations to insert (in new but not in DB)
        $toInsert = array_filter($newCombinations, fn($row) => !in_array($row, $existing));

        // Apply only diffs for efficiency
        if (!empty($toDelete)) {
            $this->deleteMany([$field2 => $field2data, $field1 => implode(',', $toDelete)]);
        }

        $arrayToInsert = [];
        foreach ($toInsert as $item) {
            $resultItem[$field1] = $item;
            $resultItem[$field2] = $field2data;
            $arrayToInsert[] = $resultItem;
        }

        if (!empty($arrayToInsert)) {
            $this->saveMany($arrayToInsert);
        }
    }

    /**
     * Delete a single record by ID
     *
     * @param int $id Record ID to delete
     */
    public function delete($id)
    {
        $modelName = $this->modelName;
        if (isset($id) && ($item = $modelName::findOrFail($id))) {
            $item->delete();
        }
    }

    /**
     * Delete multiple records based on filter criteria
     *
     * @param array $filter Filter criteria
     */
    public function deleteMany($filter): void
    {
        if (!isset($filter['filter'])) {
            $filter['filter'] = $filter;
        }
        $modelName = $this->modelName;
        $result = $modelName::search($filter);
        foreach ($result as $item)
            $item->delete();
    }

    // ===== ABSTRACT METHODS TO BE IMPLEMENTED BY CONCRETE CLASSES =====

    /**
     * Step 1: Prepare and transform input data
     *
     * Use this method to:
     * - Set default values
     * - Transform data structures
     * - Generate computed fields
     * - Clean/normalize input
     *
     * @param array $params Raw input parameters
     * @return array Prepared parameters
     */
    public function prepareArray($params)
    {
        return $params;
    }

    /**
     * Step 2: Validate business rules
     *
     * Use this method to:
     * - Check required fields
     * - Validate business logic
     * - Check status transitions
     * - Throw exceptions for validation failures
     *
     * @param array $params Prepared parameters
     * @throws Exception
     */
    public function validate($params) {}

    /**
     * Step 4: Save related/child data after main model is saved
     *
     * Use this method to:
     * - Save pivot table relationships
     * - Create child records
     * - Update related models
     * - Save transaction data
     *
     * @param mixed $model The saved main model
     * @param array $params Prepared parameters
     */
    public function saveRelatedData($model, $params) {}

    /**
     * Step 5: Post-save operations (calculations, notifications, etc.)
     *
     * Use this method to:
     * - Calculate totals/aggregates
     * - Send notifications
     * - Clear caches
     * - Log activities
     * - Update calculated fields
     *
     * @param mixed $model The saved main model
     * @param array $params Prepared parameters
     */
    public function afterSave($model, $params)
    {
        // Default: Handle image uploads if present
        if (isset($params['image'])) {
            $model->clearMediaCollection('image');
            $model->addMedia($params['image'])->toMediaCollection('image');
        }
    }

    /**
     * Post-commit operations (executed after successful transaction)
     *
     * Use this method to:
     * - Send emails
     * - Trigger external APIs
     * - Queue background jobs
     * - Any operations that should only run after successful commit
     *
     * @param mixed $result The saved and refreshed model
     * @param array $params Prepared parameters
     */
    public function afterCommit($result, $params) {}

    /**
     * Check if a parameter has changed from the original value
     *
     * @param string $paramName Parameter name to check
     * @param array $params Current parameters
     * @param mixed $paramObject Optional existing model object
     * @return bool True if the parameter has changed
     */
    public function isParamChanged($paramName, $params, $paramObject = null)
    {
        if (!isset($params['id'])) return false;

        if (!$paramObject) {
            $modelName = $this->modelName;
            $modelObject = $modelName::findOrFail($params['id']);
        }

        if (!isset($params[$paramName])) return false;

        if (!isset($modelObject->$paramName) && isset($params[$paramName]) && $params[$paramName]) return true;

        if (!isset($modelObject->$paramName) || $params[$paramName] == $modelObject->$paramName) return false;

        return true;
    }
}
