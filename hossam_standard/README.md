# Hossam Standard - Laravel Development Framework

A comprehensive set of standardized classes for Laravel application development, providing consistent patterns for data saving, searching, and filtering operations.

## 📁 Structure

```
hossam_standard/
├── Services/
│   └── BaseSavingService.php     # Standardized data saving pattern
├── Utils/
│   └── ExtendQueryBuilder.php    # Advanced query building with filters
├── Traits/
│   └── Searchable.php           # Search capabilities for models
└── Filters/
    └── BaseFilter.php           # Base class for custom filters
```

## 🚀 Quick Start

### 1. Add Searchable Trait to Your Models

```php
<?php

namespace App\Models;

use HossamStandard\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Searchable;

    // Your model code...
}
```

### 2. Create Saving Services

```php
<?php

namespace App\Services\Saving;

use HossamStandard\Services\BaseSavingService;
use App\Models\User;

class UserSavingService extends BaseSavingService
{
    public string $modelName = User::class;

    public function prepareArray($params)
    {
        // Set defaults, transform data, etc.
        $params['password'] = bcrypt($params['password']);
        return $params;
    }

    public function validate($params)
    {
        // Business logic validation
        if (isset($params['email'])) {
            // Check if email is unique
        }
    }

    public function saveRelatedData($model, $params)
    {
        // Save related data like roles, permissions, etc.
    }
}
```

### 3. Create Custom Filters (Optional)

```php
<?php

namespace App\Filters;

use HossamStandard\Filters\BaseFilter;

class UserFilter extends BaseFilter
{
    public function status($value): void
    {
        if ($value === 'active') {
            $this->query->where('status', 'active')
                       ->whereNull('suspended_at');
        }
    }

    public function allowedSearchFields(): array
    {
        return ['id', 'name', 'email', 'status', 'created_at'];
    }
}
```

## 📖 Core Concepts

### The Save & Commit Pattern

**ALWAYS use `saveAndCommit()` for data integrity:**

```php
// ✅ CORRECT - Uses transaction
$user = app(UserSavingService::class)->saveAndCommit([
    'name' => 'John Doe',
    'email' => 'john@example.com'
]);

// ❌ WRONG - No transaction safety
$user = app(UserSavingService::class)->save([
    'name' => 'John Doe',
    'email' => 'john@example.com'
]);
```

### The Search Pattern

**ALWAYS use the `search()` methods for data retrieval:**

```php
// ✅ CORRECT - Uses Hossam Standard
$users = User::search([
    'filter' => ['status' => 'active'],
    'sort' => '-created_at',
    'limit' => 10
]);

// ❌ WRONG - Direct Eloquent queries
$users = User::where('status', 'active')
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();
```

## 🔧 BaseSavingService Methods

### Required Methods (Must Implement)

#### `prepareArray($params)` - Data Preparation
```php
public function prepareArray($params)
{
    // Set default values
    $params['created_by'] = auth()->id();

    // Transform data
    if (isset($params['full_name'])) {
        $parts = explode(' ', $params['full_name']);
        $params['first_name'] = $parts[0] ?? '';
        $params['last_name'] = $parts[1] ?? '';
    }

    // Generate computed fields
    if (!isset($params['code'])) {
        $params['code'] = 'ORD-' . time();
    }

    return $params;
}
```

#### `validate($params)` - Business Logic Validation
```php
public function validate($params)
{
    // Check business rules
    if ($params['status'] === 'approved' && !$this->canApprove($params)) {
        throw new \Exception('Cannot approve: insufficient permissions');
    }

    // Validate relationships
    if (isset($params['category_id'])) {
        $category = Category::find($params['category_id']);
        if (!$category) {
            throw new \Exception('Invalid category selected');
        }
    }
}
```

#### `saveRelatedData($model, $params)` - Related Data Saving
```php
public function saveRelatedData($model, $params)
{
    // Save many-to-many relationships
    if (isset($params['tags'])) {
        $this->saveManyToMany([
            'tag_id' => $params['tags'],
            'taggable_id' => $model->id,
            'taggable_type' => $model->getMorphClass()
        ]);
    }

    // Save child records
    if (isset($params['addresses'])) {
        app(AddressSavingService::class)->saveMany($params['addresses'], [
            'user_id' => $model->id
        ]);
    }
}
```

### Optional Methods (Can Override)

#### `afterSave($model, $params)` - Post-Save Operations
```php
public function afterSave($model, $params)
{
    // Send notifications
    Mail::to($model->email)->send(new WelcomeEmail($model));

    // Clear cache
    Cache::forget("user_{$model->id}");

    // Log activity
    activity()->on($model)->log('User created');
}
```

#### `afterCommit($result, $params)` - Post-Transaction Operations
```php
public function afterCommit($result, $params)
{
    // External API calls (only after successful commit)
    $this->syncToExternalService($result);

    // Queue background jobs
    dispatch(new ProcessUserWelcome($result));
}
```

## 🔍 Searchable Trait Methods

### `search($params)` - Get Collection
```php
$users = User::search([
    'filter' => [
        'status' => 'active',
        'name:like' => 'John',
        'age:gt' => 18
    ],
    'sort' => '-created_at',
    'limit' => 20,
    'with' => ['posts', 'roles']
]);
```

### `searchFirst($params)` - Get Single Record
```php
$user = User::searchFirst([
    'filter' => ['email' => 'john@example.com']
]);
```

### `searchCount($params)` - Get Count
```php
$activeUsersCount = User::searchCount([
    'filter' => ['status' => 'active']
]);
```

### `searchQuery($params)` - Get Query Builder
```php
$query = User::searchQuery(['filter' => ['status' => 'active']]);
$paginatedUsers = $query->paginate(15);
```

## 🔎 Filter Operators

| Operator | Description | Example |
|----------|-------------|---------|
| `eq` (default) | Equals | `'status' => 'active'` |
| `gt` | Greater than | `'age:gt' => 18` |
| `gte` | Greater than or equal | `'price:gte' => 100` |
| `lt` | Less than | `'age:lt' => 65` |
| `lte` | Less than or equal | `'price:lte' => 1000` |
| `like` | LIKE with wildcards | `'name:like' => 'John'` |
| `notlike` | NOT LIKE | `'name:notlike' => 'admin'` |
| `in` | IN array | `'status:in' => 'active,pending'` |
| `notin` | NOT IN array | `'id:notin' => '1,2,3'` |
| `isnull` | IS NULL | `'deleted_at:isnull' => 1` |
| `isnotnull` | IS NOT NULL | `'verified_at:isnotnull' => 1` |
| `not` | NOT equal or IS NULL | `'status:not' => 'banned'` |

## 🎯 Advanced Filtering

### Custom Filter Classes

```php
<?php

namespace App\Filters;

use HossamStandard\Filters\BaseFilter;

class UserFilter extends BaseFilter
{
    public function status($value): void
    {
        switch ($value) {
            case 'active':
                $this->query->where('status', 'active')
                           ->whereNull('suspended_at');
                break;
            case 'suspended':
                $this->query->whereNotNull('suspended_at');
                break;
            case 'inactive':
                $this->query->where('status', 'inactive');
                break;
        }
    }

    public function age_range($value): void
    {
        if (is_string($value) && strpos($value, '-') !== false) {
            [$min, $max] = explode('-', $value);
            $this->query->whereBetween('age', [(int)$min, (int)$max]);
        }
    }

    public function has_posts($value): void
    {
        if ($value) {
            $this->query->whereHas('posts');
        }
    }

    public function prepareFiltersArray($filters)
    {
        // Set defaults
        $filters['status'] ??= 'active';

        // Transform search terms
        if (isset($filters['q'])) {
            $filters['name:like'] = $filters['q'];
            $filters['email:like'] = $filters['q'];
            unset($filters['q']);
        }

        return $filters;
    }

    public function allowedSearchFields(): array
    {
        return ['id', 'name', 'email', 'status', 'age', 'created_at'];
    }
}
```

### Usage with Custom Filters

```php
$users = User::search([
    'filter' => [
        'status' => 'active',           // Uses UserFilter::status()
        'has_posts' => true,            // Uses UserFilter::has_posts()
        'age_range' => '25-35',         // Uses UserFilter::age_range()
        'name:like' => 'John'           // Built-in operator
    ]
]);
```

## 🔗 Relationship Filtering

### Filter by Related Model Fields

```php
// Filter posts by user status
$posts = Post::search([
    'filter' => [
        'user' => [
            'status' => 'active',
            'name:like' => 'John'
        ]
    ],
    'with' => ['user']
]);

// Filter orders by customer type
$orders = Order::search([
    'filter' => [
        'customer' => [
            'type' => 'premium',
            'country' => 'US'
        ]
    ]
]);
```

## 📊 DataTables Integration

### Basic DataTable Setup

```php
<?php

namespace App\DataTables;

use HossamStandard\Traits\DatatableHelper;
use App\Models\User;
use Yajra\DataTables\EloquentDataTable;

class UsersDatatable extends BaseDataTable
{
    use DatatableHelper;

    protected function model(): string
    {
        return User::class;
    }

    public function dataTable($query): EloquentDataTable
    {
        $result = datatables()->eloquent($query);

        $result->addColumn('name', fn ($model) => $model->name ?? '')
               ->addColumn('email', fn ($model) => $model->email ?? '')
               ->addColumn('status', fn ($model) => $model->getStatusBadge())
               ->addColumn('action', fn($data) => view('temp', [
                   'actions' => $this->getActionButtons($data)
               ]));

        return $result->rawColumns(['status', 'action']);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('name')->title('Name'),
            Column::make('email')->title('Email'),
            Column::make('status')->title('Status'),
            Column::computed('action')->title('Actions')
        ];
    }

    public function query()
    {
        $filter = ['sort' => '-id'];

        // Apply search keyword
        if ($keyword = $this->request->input('search.value')) {
            $filter['filter']['name:like'] = $keyword;
            $filter['filter']['email:like'] = $keyword;
        }

        return $this->model()::searchQuery($filter);
    }
}
```

## 🧪 Testing Examples

### Testing Saving Services

```php
<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\Saving\UserSavingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSavingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_user_with_save_and_commit()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123'
        ];

        $user = app(UserSavingService::class)->saveAndCommit($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
    }

    public function test_validation_fails_for_invalid_data()
    {
        $this->expectException(\Exception::class);

        $data = [
            'name' => '', // Invalid: empty name
            'email' => 'invalid-email' // Invalid: bad format
        ];

        app(UserSavingService::class)->saveAndCommit($data);
    }
}
```

### Testing Search Functionality

```php
<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_search_users_by_status()
    {
        User::factory()->create(['status' => 'active']);
        User::factory()->create(['status' => 'inactive']);

        $activeUsers = User::search([
            'filter' => ['status' => 'active']
        ]);

        $this->assertCount(1, $activeUsers);
        $this->assertEquals('active', $activeUsers->first()->status);
    }

    public function test_can_search_with_operators()
    {
        User::factory()->create(['age' => 25]);
        User::factory()->create(['age' => 35]);

        $youngUsers = User::search([
            'filter' => ['age:lt' => 30]
        ]);

        $this->assertCount(1, $youngUsers);
        $this->assertEquals(25, $youngUsers->first()->age);
    }
}
```

## 📋 Best Practices

### ✅ DOs

1. **Always use `saveAndCommit()`** for data integrity
2. **Always use `search()` methods** for data retrieval
3. **Implement all required methods** in saving services
4. **Use custom filters** for complex business logic
5. **Validate data** in the `validate()` method
6. **Keep transactions short** - move non-critical operations to `afterCommit()`
7. **Use meaningful method names** and clear documentation

### ❌ DON'Ts

1. **Don't use direct Eloquent queries** for complex operations
2. **Don't skip validation** - always validate business rules
3. **Don't save data outside the standard flow** (except in `afterSave()` for calculations)
4. **Don't forget to handle related data** in `saveRelatedData()`
5. **Don't make external API calls** inside transactions
6. **Don't use raw SQL** when the standard provides operators

### 🔒 Security Considerations

1. **Always define `allowedSearchFields()`** in filter classes
2. **Validate all input parameters** in `validate()` methods
3. **Use parameterized queries** (handled automatically by Eloquent)
4. **Check permissions** before data operations
5. **Log sensitive operations** in `afterSave()` or `afterCommit()`

### ⚡ Performance Tips

1. **Use `storeManyFast()`** for bulk inserts without relationships
2. **Leverage `with` parameter** to eager load relationships
3. **Use `select` parameter** to limit retrieved columns
4. **Apply `limit` parameter** to prevent large result sets
5. **Use `hasJoinedBefore()`** to prevent duplicate joins in custom filters

## 🔄 Migration Guide

### From Raw Eloquent to Hossam Standard

**Before:**
```php
// Saving
$user = new User();
$user->fill($data);
$user->save();

// Searching
$users = User::where('status', 'active')
    ->where('name', 'LIKE', '%John%')
    ->with(['posts', 'roles'])
    ->orderBy('created_at', 'desc')
    ->get();
```

**After:**
```php
// Saving
$user = app(UserSavingService::class)->saveAndCommit($data);

// Searching
$users = User::search([
    'filter' => [
        'status' => 'active',
        'name:like' => 'John'
    ],
    'with' => ['posts', 'roles'],
    'sort' => '-created_at'
]);
```

## 📚 API Reference

### BaseSavingService Methods

- `save($params)` - Execute the save flow without transaction
- `saveAndCommit($params)` - Execute save flow with transaction (RECOMMENDED)
- `saveMany($params, $extraParams)` - Save multiple records
- `storeManyFast($params, $extraParams)` - Bulk insert without relationships
- `delete($id)` - Delete single record
- `deleteMany($filter)` - Delete multiple records

### Searchable Trait Methods

- `search($params)` - Return collection
- `searchFirst($params)` - Return first result
- `searchCount($params)` - Return count
- `searchQuery($params)` - Return query builder

### Filter Operators

All operators use the format: `field:operator` (except default `eq`)

### Reserved Parameters

- `filter` - Array of filters to apply
- `sort` - Sort field (prefix with `-` for DESC)
- `select` - Fields to select
- `with` - Relationships to eager load
- `with_group` - Predefined relationship groups
- `limit` - Maximum results to return
- `group_by` - Group by field (default: table.id)
- `disable_custom_filters` - Skip custom filter classes
- `disable_joins` - Skip automatic relationship joins
- `without_global_scope` - Remove global scopes

---

**Remember**: Consistency is key. All team members should follow these patterns to ensure maintainable, secure, and performant code.
