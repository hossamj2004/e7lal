<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Base Filter Class - Hossam Standard
 *
 * Abstract base class for implementing custom filters on Eloquent models.
 * Provides the foundation for advanced filtering capabilities with security controls.
 *
 * Key Features:
 * - Method-based filtering (each filter becomes a method)
 * - Security through allowedSearchFields()
 * - Automatic join management to prevent duplicates
 * - Filter preparation and transformation
 *
 * @author Hossam Hassan
 * @version 1.0
 */
abstract class BaseFilter
{
    /**
     * The query builder instance
     * @var Builder
     */
    protected $query;

    /**
     * Constructor
     *
     * @param Builder $query The query builder to apply filters to
     */
    public function __construct($query = null)
    {
        $this->query = $query;
    }

    /**
     * Apply filters to the query builder
     *
     * This method iterates through the filters array and calls corresponding
     * methods on the filter class for each filter key.
     *
     * @param array $filters Array of filters to apply
     * @return Builder The query builder with applied filters
     */
    public function apply(array $filters): Builder
    {
        foreach ($filters as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }
        return $this->query;
    }

    /**
     * Prepare and transform the filters array before applying
     *
     * Use this method to:
     * - Set default filter values
     * - Transform filter keys/values
     * - Add computed filters
     * - Remove invalid filters
     *
     * @param array $filters Raw filters array
     * @return array Prepared filters array
     */
    public function prepareFiltersArray($filters)
    {
        return $filters;
    }

    /**
     * Check if a table has already been joined to prevent duplicate joins
     *
     * @param string $table The table name to check (e.g., "users as users")
     * @return bool True if the table has already been joined
     */
    protected function hasJoinedBefore(string $table): bool
    {
        $joins = $this->query->getQuery()->joins;
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
     * Get the list of allowed searchable fields for security
     *
     * Override this method to return an array of database column names
     * that are allowed to be searched/filtered. This prevents unauthorized
     * access to sensitive fields.
     *
     * @return array Array of allowed field names
     */
    public function allowedSearchFields(): array
    {
        return [
            // Override in concrete classes to specify allowed fields
            // Example: return ['id', 'name', 'email', 'status', 'created_at'];
        ];
    }

    /**
     * Define relationship groups for eager loading
     *
     * Use this method to define named groups of relationships that can be
     * loaded together for performance optimization.
     *
     * @return array Array of relationship groups
     *
     * @example
     * return [
     *     'basic' => ['user', 'category'],
     *     'detailed' => ['user.profile', 'category.posts', 'tags'],
     *     'admin' => ['user', 'category', 'logs', 'permissions']
     * ];
     */
    public function getWithGroups(): array
    {
        return [];
    }
}
