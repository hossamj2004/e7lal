<?php

namespace App\Traits;

use App\Utils\ExtendQueryBuilder;

/**
 * Searchable Trait - Hossam Standard
 *
 * This trait provides advanced search capabilities to Eloquent models using the ExtendQueryBuilder.
 * It enables models to perform complex queries with filtering, sorting, relationships, and more.
 *
 * Methods provided:
 * - search($params) - Get collection of results
 * - searchFirst($params) - Get first result
 * - searchCount($params) - Get count of results
 * - searchQuery($params) - Get query builder instance
 * - returnData($data, $fields) - Access nested object properties dynamically
 *
 * @author Hossam Hassan
 * @version 1.0
 */
trait Searchable
{
    /**
     * Perform a search query and return results as a collection.
     *
     * @param array $params Search parameters including filters, sorting, relationships, etc.
     * @return \Illuminate\Database\Eloquent\Collection
     *
     * @example
     * User::search([
     *     'filter' => [
     *         'status' => 'active',
     *         'name:like' => 'John'
     *     ],
     *     'sort' => '-created_at',
     *     'limit' => 10,
     *     'with' => ['posts', 'roles']
     * ]);
     */
    public static function search($params = [])
    {
        if (!isset($params['filter'])) {
            $params['filter'] = $params;
        }
        $modelName = static::class;
        $data = ExtendQueryBuilder::for($modelName, $params);
        return $data->get();
    }

    /**
     * Perform a search query and return the first result.
     *
     * @param array $params Search parameters
     * @return \Illuminate\Database\Eloquent\Model|null
     *
     * @example
     * $user = User::searchFirst([
     *     'filter' => [
     *         'email' => 'john@example.com',
     *         'status' => 'active'
     *     ]
     * ]);
     */
    public static function searchFirst($params = [])
    {
        if (!isset($params['filter'])) {
            $params['filter'] = $params;
        }
        $modelName = static::class;
        $data = ExtendQueryBuilder::for($modelName, $params);
        return $data->first();
    }

    /**
     * Perform a search query and return the count of results.
     *
     * @param array $params Search parameters
     * @return int
     *
     * @example
     * $count = User::searchCount([
     *     'filter' => ['status' => 'active']
     * ]);
     */
    public static function searchCount($params = [])
    {
        if (!isset($params['filter'])) {
            $params['filter'] = $params;
        }
        $modelName = static::class;
        $params['group_by'] = false; // Disable grouping for count queries
        $data = ExtendQueryBuilder::for($modelName, $params);
        return $data
            ->selectRaw('COUNT(DISTINCT ' . (new $modelName())->getTable() . '.id) as distinct_count')
            ->value('distinct_count');
    }

    /**
     * Perform a search query and return the query builder instance.
     *
     * Useful for further query modifications or pagination.
     *
     * @param array $params Search parameters
     * @return \Illuminate\Database\Eloquent\Builder
     *
     * @example
     * $query = User::searchQuery([
     *     'filter' => ['status' => 'active']
     * ]);
     * $users = $query->paginate(20);
     */
    public static function searchQuery($params = [])
    {
        if (!isset($params['filter'])) {
            $params['filter'] = $params;
        }
        $modelName = static::class;
        return ExtendQueryBuilder::for($modelName, $params);
    }

    /**
     * Dynamically access nested object properties using dot notation.
     *
     * This method allows accessing complex nested relationships and method calls
     * using a string notation like "user.profile.address.city".
     *
     * @param string $data Dot-notation path to access
     * @param array $fields Optional field replacements for method parameters
     * @return mixed The accessed value or null if not found
     *
     * @example
     * // Access relationship property
     * $city = $user->returnData('profile.address.city');
     *
     * // Call method with parameters
     * $result = $user->returnData('calculateTotal(param1)', ['param1' => 'value']);
     */
    public function returnData($data, $fields = [])
    {
        $data = explode('->', $data);

        // Performance optimization: cache the moving object
        if (!$this->movingObject) {
            $this->movingObject = $this;
        }
        $movingObject = $this->movingObject;

        if (!$movingObject) {
            $currentClass = get_called_class();
            $movingObject = new $currentClass;
        }

        foreach ($data as $block) {
            // Parse method calls with parameters: methodName(param1,param2)
            $block = explode('(', $block);
            $mainName = $block[0];

            // Parse parameters if they exist
            if (count($block) > 1) {
                $block[1] = explode(')', $block[1])[0];
                $block[1] = explode(',', $block[1]);
                $variablesArray = $block[1];
                if ($variablesArray[0] == '') $variablesArray = [];
            } else {
                $variablesArray = [];
            }

            // Replace parameter placeholders with actual field values
            foreach ($variablesArray as $key => $item) {
                if (isset($fields[$item])) {
                    $variablesArray[$key] = $fields[$item];
                }
            }

            // Try to access as property first
            if (count($variablesArray) == 0) {
                try {
                    if (isset($movingObject->$mainName)) {
                        $movingObject = $movingObject->$mainName;
                        continue;
                    }
                } catch (\Exception $e) {
                    // Property doesn't exist, continue to method check
                }
            }

            // Try to call as method
            if ($movingObject) {
                if (method_exists($movingObject, $mainName) || count($variablesArray) > 0) {
                    try {
                        $movingObject = @call_user_func_array(
                            array($movingObject, get_class($movingObject) . '::' . $mainName),
                            $variablesArray
                        );
                        continue;
                    } catch (\Exception $e) {
                        // Method call failed
                    }
                }
            }

            // If we can't access the property or call the method, return null
            $movingObject = '';
        }

        // Clean up the cached object
        unset($this->movingObject);
        return $movingObject;
    }
}
