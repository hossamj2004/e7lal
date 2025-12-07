<?php

namespace App\Utils;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use ReflectionMethod;

/**
 * Extend Query Builder - Hossam Standard
 *
 * A powerful query builder that provides advanced search, filtering, sorting, and relationship
 * loading capabilities for Laravel Eloquent models.
 *
 * Key Features:
 * - Advanced filtering with operators (gt, lt, like, in, etc.)
 * - Automatic relationship joins
 * - Custom filter classes support
 * - Sorting and pagination
 * - Relationship eager loading
 * - Security through allowed search fields
 *
 * @author Hossam Hassan
 * @version 1.0
 */
class ExtendQueryBuilder
{
    /**
     * Create a query builder instance for the given model and apply filters, sorting, and grouping.
     *
     * Supported parameters:
     * - select: Fields to select (default: table.*)
     * - filter: Array of filters to apply
     * - sort: Sort field (prefix with '-' for DESC)
     * - group_by: Group by field (default: table.id)
     * - with: Relationships to eager load
     * - with_group: Predefined relationship groups
     * - limit: Limit number of results
     * - disable_custom_filters: Skip custom filter classes
     * - disable_joins: Skip automatic joins
     * - without_global_scope: Remove global scopes
     *
     * @param string $modelName The model name (e.g., "User")
     * @param array $params The parameters for the query
     * @return Builder The resulting query builder
     */
    public static function for(string $modelName, array $params): Builder
    {
        // Initialize the model
        $model = self::initializeModel($modelName);

        // Start building the query
        $query = $model->newQuery();

        // Apply SELECT clause
        $query->select($params['select'] ?? $model->getTable() . '.*');

        // Apply filters
        if (isset($params['filter'])) {
            $query = self::applyFilters($model, $query, $params['filter'], $params);
        }

        // Apply sorting
        if (isset($params['sort'])) {
            $query = self::applySort($query, $params['sort']);
        }

        // Apply grouping
        $disableGrouping = isset($params['group_by']) && $params['group_by'] === false;
        if (!$disableGrouping) {
            $query->groupBy($params['group_by'] ?? $model->getTable() . '.id');
        }

        // Apply with_groups (predefined relationship sets)
        if (isset($params['with_group']) && !isset($params['with'])) {
            $params['with'] = self::getWithFromWithGroup($model, $params['with_group']);
        }

        // Apply eager loading
        if (isset($params['with'])) {
            $query->with($params['with']);
        }

        // Apply limit
        if (isset($params['limit'])) {
            $query->limit($params['limit']);
        }

        // Apply without global scope
        if (isset($params['without_global_scope'])) {
            $query->withoutGlobalScopes();
        }

        return $query;
    }

    /**
     * Initialize a model instance based on the provided class name.
     *
     * @param string $modelName The model name (e.g., "User")
     * @return Model The initialized model instance
     * @throws \InvalidArgumentException
     */
    private static function initializeModel(string $modelName): Model
    {
        if (!str_starts_with($modelName, 'App\\Models\\')) {
            $modelName = 'App\\Models\\' . $modelName;
        }
        $modelClass = $modelName;
        if (!class_exists($modelClass) || !is_subclass_of($modelClass, Model::class)) {
            throw new \InvalidArgumentException("Model class $modelClass does not exist or is not a valid model.");
        }
        return new $modelClass();
    }

    /**
     * Apply filters to the query with support for custom filter classes and operators.
     *
     * @param Model $model The model instance
     * @param Builder $query The query builder
     * @param array $filters The filters to apply
     * @param array $params Additional parameters (for controlling filter behavior)
     * @return Builder The query builder with applied filters
     */
    private static function applyFilters(Model $model, Builder $query, array $filters, array $params = []): Builder
    {
        // Apply custom filter class if it exists and not disabled
        $disableCustomFilters = in_array('disable_custom_filters', array_keys($filters)) &&
                               isset($filters['disable_custom_filters']) &&
                               $filters['disable_custom_filters'];

        if (!$disableCustomFilters) {
            $filterClass = self::resolveModelFilterClass($model);
            if ($filterClass) {
                $filterObject = (new $filterClass($query));
                $filters = $filterObject->prepareFiltersArray($filters);
                $query = $filterObject->apply($filters);
            }
        }

        // Apply automatic joins for relationships (unless disabled)
        $disableJoins = in_array('disable_joins', array_keys($filters)) &&
                       isset($filters['disable_joins']) &&
                       $filters['disable_joins'];

        if (!$disableJoins) {
            $relations = self::getJoinsFromFilters($model, $filters);
            if (count($relations) > 0) {
                $query = self::addJoinsToBuilder($query, $relations);
            }
        }

        // Apply each filter with operator support
        foreach ($filters as $field => $value) {
            // Handle nested filters (related models)
            if (is_array($value) && class_exists("App\\Models\\$field")) {
                $nestedModelClass = "App\\Models\\$field";
                $query = self::applyFilters(new $nestedModelClass, $query, $value);
            } else {
                // Apply filter for the current model
                $query = self::applyQueryFor($model, $query, $field, $value);
            }
        }

        return $query;
    }

    /**
     * Apply a single filter with operator parsing.
     *
     * Supported operators:
     * - :gt (greater than)
     * - :gte (greater than or equal)
     * - :lt (less than)
     * - :lte (less than or equal)
     * - :like (LIKE with wildcards)
     * - :notlike (NOT LIKE)
     * - :not (NOT equal or IS NULL)
     * - :isnull (IS NULL)
     * - :isnotnull (IS NOT NULL)
     * - :notin (NOT IN array)
     * - :in (IN array)
     * - default: equals or IN for comma-separated values
     *
     * @param Model $model The model instance
     * @param Builder $query The query builder
     * @param string $field The field to filter
     * @param mixed $value The value to filter by
     * @return Builder The query builder with the applied filter
     */
    private static function applyQueryFor(Model $model, Builder $query, string $field, $value): Builder
    {
        // Parse operator from field name (field:operator)
        [$field, $operator] = strpos($field, ':') !== false ? explode(':', $field) : [$field, 'eq'];

        // Security check: ensure field is in allowed search fields
        $filterClass = self::resolveModelFilterClass($model);
        if (!class_exists($filterClass)) {
            $filterClass = BaseFilter::class;
        }
        if (!in_array($field, (new $filterClass($query))->allowedSearchFields())) {
            return $query;
        }

        return self::applyQuery($model, $query, $field, $operator, $value);
    }

    /**
     * Apply a query filter based on the operator.
     *
     * @param Model $model The model instance
     * @param Builder $query The query builder
     * @param string $field The field to filter
     * @param string $operator The operator (gt, lt, like, etc.)
     * @param mixed $value The value to filter by
     * @return Builder The query builder with the applied filter
     */
    private static function applyQuery(Model $model, Builder $query, string $field, string $operator, $value): Builder
    {
        $tableName = $model->getTable();

        switch ($operator) {
            case 'gt':
                return $query->where($tableName.'.'.$field, '>', $value);
            case 'lt':
                return $query->where($tableName.'.'.$field, '<', $value);
            case 'gte':
                return $query->where($tableName.'.'.$field, '>=', $value);
            case 'lte':
                return $query->where($tableName.'.'.$field, '<=', $value);
            case 'like':
                return $query->where($tableName.'.'.$field, 'LIKE', "%$value%");
            case 'notlike':
                return $query->where($tableName.'.'.$field, 'NOT LIKE', "%$value%");
            case 'not':
                return $query->where(function ($query) use ($tableName, $field, $value) {
                    $query->where($tableName . '.' . $field, '<>', $value)
                          ->orWhereNull($tableName . '.' . $field);
                });
            case 'isnotnull':
                return $query->whereNotNull($tableName.'.'.$field);
            case 'isnull':
                return $query->whereNull($tableName.'.'.$field);
            case 'notin':
                return $query->whereNotIn($tableName.'.'.$field, explode(',', $value));
            case 'in':
                return $query->whereIn($tableName.'.'.$field, explode(',', $value));
            default:
                // Default: equals comparison, or IN for comma-separated values
                $values = explode(',', $value);
                return (count($values) > 1) ?
                    $query->whereIn($tableName.'.'.$field, $values) :
                    $query->where($tableName . '.' . $field, '=', $value);
        }
    }

    /**
     * Apply sorting to the query.
     *
     * @param Builder $query The query builder
     * @param string $sort The sort parameter (e.g., '-name' for DESC)
     * @return Builder The query builder with applied sorting
     */
    private static function applySort(Builder $query, string $sort): Builder
    {
        $direction = strpos($sort, '-') === 0 ? 'desc' : 'asc';
        $field = ltrim($sort, '-');
        return $query->orderBy($field, $direction);
    }

    /**
     * Resolve the custom filter class for the model, if it exists.
     *
     * @param Model $model The model instance
     * @return string|null The filter class or null if not found
     */
    public static function resolveModelFilterClass(Model $model): ?string
    {
        $filterClass = "App\\Filters\\" . class_basename($model) . "Filter";
        return class_exists($filterClass) ? $filterClass : null;
    }

    /**
     * Get the model class from a filter class name.
     *
     * @param string $filterClass The filter class name
     * @return string|null The model class or null if not found
     */
    public static function resolveModelFromFilterClass(string $filterClass): ?string
    {
        // Ensure it ends with 'Filter'
        if (!str_ends_with($filterClass, 'Filter')) {
            return null;
        }

        // Extract base name, remove "Filter"
        $baseName = class_basename($filterClass);
        $modelName = substr($baseName, 0, -6); // Remove 'Filter'

        $modelClass = "App\\Models\\$modelName";

        return class_exists($modelClass) ? $modelClass : null;
    }

    /**
     * Get relationships from filters and prepare joins.
     *
     * @param Model $model The model instance
     * @param array $filters The filters array
     * @return array The relations for joining
     */
    private static function getJoinsFromFilters(Model $model, array $filters): array
    {
        $joins = [];
        foreach ($filters as $relatedModel => $filter) {
            if (is_array($filter) && class_exists("App\\Models\\$relatedModel")) {
                $relationType = self::getRelationType($model, $relatedModel);
                if ($relationType) {
                    $joins[] = [
                        'type' => $relationType,
                        'current_model' => class_basename($model),
                        'related_model' => $relatedModel,
                    ];
                }
            }
        }
        return $joins;
    }

    /**
     * Add joins to the query builder based on relationship types.
     *
     * @param Builder $query The query builder
     * @param array $joins The array of joins to apply
     * @return Builder The query builder with applied joins
     */
    private static function addJoinsToBuilder(Builder $query, array $joins): Builder
    {
        foreach ($joins as $join) {
            $currentTable = (new ("App\\Models\\" . $join['current_model']))->getTable();
            $relatedTable = (new ("App\\Models\\" . $join['related_model']))->getTable();

            if ($join['type'] === 'BelongsTo') {
                $query = self::addBelongsToJoin($query, $currentTable, $relatedTable, $join['related_model']);
            } elseif ($join['type'] === 'HasMany') {
                $query = self::addHasManyJoin($query, $currentTable, $relatedTable, $join['current_model']);
            }
        }

        return $query;
    }

    /**
     * Add a BelongsTo relationship join.
     *
     * @param Builder $query The query builder
     * @param string $currentTable The current table name
     * @param string $relatedTable The related table name
     * @param string $relatedModel The related model name
     * @return Builder The query builder with applied join
     */
    private static function addBelongsToJoin(Builder $query, string $currentTable, string $relatedTable, string $relatedModel): Builder
    {
        $foreignKey = self::fromCamelToDash($relatedModel) . '_id';
        if (!self::hasJoinedBefore($query, "$relatedTable as $relatedTable")) {
            $query->leftJoin("$relatedTable as $relatedTable", "$currentTable.$foreignKey", '=', "$relatedTable.id");
        }
        return $query;
    }

    /**
     * Add a HasMany relationship join.
     *
     * @param Builder $query The query builder
     * @param string $currentTable The current table name
     * @param string $relatedTable The related table name
     * @param string $currentModel The current model name
     * @return Builder The query builder with applied join
     */
    private static function addHasManyJoin(Builder $query, string $currentTable, string $relatedTable, string $currentModel): Builder
    {
        $foreignKey = self::fromCamelToDash($currentModel) . '_id';
        if (!self::hasJoinedBefore($query, "$relatedTable as $relatedTable")) {
            $query->leftJoin("$relatedTable as $relatedTable", "$relatedTable.$foreignKey", '=', "$currentTable.id");
        }
        return $query;
    }

    /**
     * Determine the relation type (BelongsTo, HasMany, etc.) between two models.
     *
     * @param Model $model The model instance
     * @param string $relatedModel The related model name
     * @return string|null The relation type or null if not found
     */
    private static function getRelationType(Model $model, string $relatedModel): ?string
    {
        $relatedModelClass = "App\\Models\\$relatedModel";
        $methodName = lcfirst($relatedModel);

        // Check for relation method or relation by table name
        foreach ((new ReflectionClass($model))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->name === $methodName || $method->name === self::dashesToCamelCase((new $relatedModelClass())->getTable())) {
                $relation = $method->invoke($model);
                return (new ReflectionClass($relation))->getShortName();
            }
        }
        return null;
    }

    /**
     * Convert a camelCase string to a snake_case string.
     *
     * @param string $input The input string in camelCase
     * @return string The converted string in snake_case
     */
    public static function fromCamelToDash(string $input): string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $input));
    }

    /**
     * Convert a snake_case string to a camelCase string.
     *
     * @param string $string The input string in snake_case
     * @param bool $capitalizeFirstCharacter Should the first character be capitalized?
     * @return string The converted string in camelCase
     */
    private static function dashesToCamelCase(string $string, bool $capitalizeFirstCharacter = false): string
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }
        return $str;
    }

    /**
     * Check if the table has already been joined in the query.
     *
     * @param Builder $query The query builder
     * @param string $table The table name to check
     * @return bool Whether the table has already been joined
     */
    public static function hasJoinedBefore(Builder $query, string $table): bool
    {
        $joins = $query->getQuery()->joins;
        if (!is_array($joins) && !is_object($joins)) {
            return false;
        }
        foreach ($joins as $join) {
            if (trim($join->table) === trim($table)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get relationships from with_group configuration.
     *
     * @param Model $model The model instance
     * @param string $withGroup The with_group name
     * @return array The relationships array
     */
    private static function getWithFromWithGroup($model, $withGroup)
    {
        $filterClass = self::resolveModelFilterClass($model);
        if (!$filterClass) {
            return [];
        }
        $filterObject = (new $filterClass());
        if (!method_exists($filterObject, 'getWithGroups')) {
            return [];
        }
        $groups = $filterObject->getWithGroups();
        return $groups[$withGroup] ?? [];
    }
}
