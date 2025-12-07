# Hossam Standard - Usage Examples

This document provides comprehensive examples of how to implement and use the Hossam Standard classes in real Laravel applications.

## 📝 Complete Implementation Examples

### Example 1: User Management System

#### 1. Model with Searchable Trait

```php
<?php

namespace App\Models;

use HossamStandard\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use Searchable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'role_id',
        'department_id',
        'phone',
        'avatar',
        'email_verified_at',
        'last_login_at'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Helper methods
    public function getStatusBadge()
    {
        return match($this->status) {
            'active' => '<span class="badge bg-success">Active</span>',
            'inactive' => '<span class="badge bg-secondary">Inactive</span>',
            'suspended' => '<span class="badge bg-warning">Suspended</span>',
            default => '<span class="badge bg-light">Unknown</span>'
        };
    }

    public function isActive()
    {
        return $this->status === 'active' && !$this->trashed();
    }
}
```

#### 2. User Filter Class

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
                $this->query->where('users.status', 'active')
                           ->whereNull('users.deleted_at');
                break;
            case 'inactive':
                $this->query->where('users.status', 'inactive');
                break;
            case 'suspended':
                $this->query->where('users.status', 'suspended');
                break;
            case 'all':
                // No additional conditions for 'all'
                break;
        }
    }

    public function role($value): void
    {
        if (!$this->hasJoinedBefore('roles as roles')) {
            $this->query->leftJoin('roles as roles', 'users.role_id', '=', 'roles.id');
        }
        $this->query->where('roles.id', $value);
    }

    public function department($value): void
    {
        if (!$this->hasJoinedBefore('departments as departments')) {
            $this->query->leftJoin('departments as departments', 'users.department_id', '=', 'departments.id');
        }
        $this->query->where('departments.id', $value);
    }

    public function has_posts($value): void
    {
        if ($value) {
            $this->query->whereHas('posts');
        } else {
            $this->query->whereDoesntHave('posts');
        }
    }

    public function verified($value): void
    {
        if ($value) {
            $this->query->whereNotNull('users.email_verified_at');
        } else {
            $this->query->whereNull('users.email_verified_at');
        }
    }

    public function last_login($value): void
    {
        // $value could be 'today', 'week', 'month', or a date range
        switch ($value) {
            case 'today':
                $this->query->whereDate('users.last_login_at', today());
                break;
            case 'week':
                $this->query->where('users.last_login_at', '>=', now()->startOfWeek());
                break;
            case 'month':
                $this->query->where('users.last_login_at', '>=', now()->startOfMonth());
                break;
        }
    }

    public function prepareFiltersArray($filters)
    {
        // Set default status to active if not specified
        $filters['status'] ??= 'active';

        // Transform search query into multiple field searches
        if (isset($filters['q'])) {
            $searchTerm = $filters['q'];
            $filters['name:like'] = $searchTerm;
            $filters['email:like'] = $searchTerm;
            unset($filters['q']);
        }

        // Handle date range filters
        if (isset($filters['created_from'])) {
            $filters['created_at:gte'] = $filters['created_from'];
            unset($filters['created_from']);
        }

        if (isset($filters['created_to'])) {
            $filters['created_at:lte'] = $filters['created_to'];
            unset($filters['created_to']);
        }

        return $filters;
    }

    public function allowedSearchFields(): array
    {
        return [
            'id',
            'name',
            'email',
            'status',
            'role_id',
            'department_id',
            'phone',
            'created_at',
            'updated_at',
            'email_verified_at',
            'last_login_at'
        ];
    }

    public function getWithGroups(): array
    {
        return [
            'basic' => ['role', 'department'],
            'detailed' => ['role', 'department', 'posts'],
            'admin' => ['role.permissions', 'department.manager', 'posts.comments']
        ];
    }
}
```

#### 3. User Saving Service

```php
<?php

namespace App\Services\Saving;

use HossamStandard\Services\BaseSavingService;
use App\Models\User;
use App\Enums\UserStatus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSavingService extends BaseSavingService
{
    public string $modelName = User::class;

    public function prepareArray($params)
    {
        // Handle password hashing
        if (isset($params['password']) && !empty($params['password'])) {
            $params['password'] = Hash::make($params['password']);
        } elseif (!isset($params['id'])) {
            // Generate random password for new users if not provided
            $params['password'] = Hash::make(Str::random(12));
        }

        // Set email verification timestamp if email changed
        if (isset($params['email']) && isset($params['id'])) {
            if ($this->isParamChanged('email', $params)) {
                // Email changed, require re-verification
                $params['email_verified_at'] = null;
            }
        }

        // Set default status for new users
        if (!isset($params['id'])) {
            $params['status'] = $params['status'] ?? UserStatus::ACTIVE->value;
        }

        // Generate avatar filename if uploaded
        if (isset($params['avatar_file'])) {
            $params['avatar'] = $this->generateAvatarFilename($params['avatar_file']);
        }

        return $params;
    }

    public function validate($params)
    {
        // Check email uniqueness
        $query = User::where('email', $params['email']);

        if (isset($params['id'])) {
            $query->where('id', '!=', $params['id']);
        }

        if ($query->exists()) {
            throw new \Exception('Email address is already in use');
        }

        // Validate role exists and is active
        if (isset($params['role_id'])) {
            $role = \App\Models\Role::find($params['role_id']);
            if (!$role) {
                throw new \Exception('Selected role does not exist');
            }
            if (!$role->is_active) {
                throw new \Exception('Selected role is not active');
            }
        }

        // Validate department exists
        if (isset($params['department_id'])) {
            $department = \App\Models\Department::find($params['department_id']);
            if (!$department) {
                throw new \Exception('Selected department does not exist');
            }
        }

        // Business rule: Department managers must have manager role
        if (isset($params['department_id']) && isset($params['role_id'])) {
            $department = \App\Models\Department::find($params['department_id']);
            if ($department && $department->manager_id == $params['id']) {
                $role = \App\Models\Role::find($params['role_id']);
                if ($role && !$role->is_manager_role) {
                    throw new \Exception('Department managers must have a manager role');
                }
            }
        }
    }

    public function saveRelatedData($model, $params)
    {
        // Handle avatar upload
        if (isset($params['avatar_file'])) {
            $model->clearMediaCollection('avatar');
            $model->addMedia($params['avatar_file'])
                  ->usingName($params['avatar'])
                  ->toMediaCollection('avatar');
        }
    }

    public function afterSave($model, $params)
    {
        // Update department manager if this user is set as manager
        if (isset($params['department_id'])) {
            $department = \App\Models\Department::find($params['department_id']);
            if ($department && $department->manager_id != $model->id) {
                $department->update(['manager_id' => $model->id]);
            }
        }

        // Clear user cache
        \Illuminate\Support\Facades\Cache::forget("user_{$model->id}");
        \Illuminate\Support\Facades\Cache::forget("user_permissions_{$model->id}");
    }

    public function afterCommit($result, $params)
    {
        // Send welcome email for new users
        if (!isset($params['id'])) {
            \App\Jobs\SendWelcomeEmail::dispatch($result);
        }

        // Send email verification if email changed
        if (isset($params['id']) && $this->isParamChanged('email', $params)) {
            $result->sendEmailVerificationNotification();
        }

        // Log activity
        activity()
            ->performedOn($result)
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => $params])
            ->log(isset($params['id']) ? 'User updated' : 'User created');
    }

    private function generateAvatarFilename($file): string
    {
        $extension = $file->getClientOriginalExtension();
        return 'avatar_' . time() . '_' . Str::random(10) . '.' . $extension;
    }
}
```

#### 4. Controller Implementation

```php
<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UsersDatatable;
use App\Models\User;
use App\Services\Saving\UserSavingService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        return (new UsersDatatable())->render('admin.users.index');
    }

    public function create()
    {
        $configuration = [
            'submit_route' => route('admin.users.store'),
            'cancel_route' => route('admin.users.index'),
            'method' => 'POST'
        ];

        return view('admin.users.create', compact('configuration'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:8',
            'status' => 'required|in:active,inactive,suspended',
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'nullable|exists:departments,id',
            'phone' => 'nullable|string|max:20',
            'avatar_file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $user = app(UserSavingService::class)->saveAndCommit($validated);

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User created successfully');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $configuration = [
            'submit_route' => route('admin.users.update', $user),
            'cancel_route' => route('admin.users.index'),
            'method' => 'PUT'
        ];

        return view('admin.users.edit', compact('user', 'configuration'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:8',
            'status' => 'required|in:active,inactive,suspended',
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'nullable|exists:departments,id',
            'phone' => 'nullable|string|max:20',
            'avatar_file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Add user ID for update operation
        $validated['id'] = $user->id;

        try {
            $updatedUser = app(UserSavingService::class)->saveAndCommit($validated);

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User updated successfully');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(User $user)
    {
        try {
            app(UserSavingService::class)->delete($user->id);

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
```

#### 5. DataTable Implementation

```php
<?php

namespace App\DataTables;

use HossamStandard\Traits\DatatableHelper;
use App\Models\User;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;

class UsersDatatable extends BaseDataTable
{
    use DatatableHelper;

    protected function model(): string
    {
        return User::class;
    }

    public function getActionButtons($data)
    {
        return [
            DataTableAction::make('show')
                ->label('View')
                ->icon('bx bx-show')
                ->data($data)
                ->url(route('admin.users.show', [$data->id]))
                ->modalView('modalMD')
                ->visible(true),

            DataTableAction::make('edit')
                ->label('Edit')
                ->icon('bx bx-edit')
                ->data($data)
                ->url(route('admin.users.edit', [$data->id]))
                ->visible(userHasPermission('edit-users')),

            DataTableAction::make('delete')
                ->label('Delete')
                ->icon('bx bx-trash')
                ->class('text-danger')
                ->data($data)
                ->confirmMessage("Are you sure?")
                ->confirmDescription('This action cannot be undone.')
                ->url(route('admin.users.destroy', [$data->id]))
                ->submit(method: 'DELETE')
                ->visible(userHasPermission('delete-users'))
        ];
    }

    public function dataTable($query): EloquentDataTable
    {
        $result = datatables()->eloquent($query);

        $result->addColumn('name', fn ($model) => $model->name ?? '')
               ->addColumn('email', fn ($model) => $model->email ?? '')
               ->addColumn('role_name', fn ($model) => $model->role->name ?? '')
               ->addColumn('department_name', fn ($model) => $model->department->name ?? '')
               ->addColumn('status', fn ($model) => $model->getStatusBadge())
               ->addColumn('created_at', fn ($model) => $model->created_at?->format('Y-m-d'))
               ->addColumn('action', fn($data) => view('temp', [
                   'actions' => $this->getActionButtons($data)
               ]));

        return $result->rawColumns(['status', 'action'])
                     ->setRowId('id');
    }

    protected function getColumns(): array
    {
        return [
            Column::make('name')->title('Name')->orderable(false)->searchable(false),
            Column::make('email')->title('Email')->orderable(false)->searchable(false),
            Column::make('role_name')->title('Role')->orderable(false)->searchable(false),
            Column::make('department_name')->title('Department')->orderable(false)->searchable(false),
            Column::make('status')->title('Status')->orderable(false)->searchable(false),
            Column::make('created_at')->title('Created')->orderable(false)->searchable(false),
            Column::computed('action')->orderable(false)->searchable(false)->width(120)->addClass('text-center')
        ];
    }

    public function query()
    {
        $filter = [
            'sort' => '-id',
            'with' => ['role', 'department']
        ];

        // Apply status filter from request
        if ($status = $this->request->input('status')) {
            $filter['filter']['status'] = $status;
        }

        // Apply role filter
        if ($roleId = $this->request->input('role_id')) {
            $filter['filter']['role'] = $roleId;
        }

        // Apply department filter
        if ($departmentId = $this->request->input('department_id')) {
            $filter['filter']['department'] = $departmentId;
        }

        // Apply search keyword
        if ($keyword = $this->request->input('search.value')) {
            $filter['filter']['q'] = $keyword;
        }

        return $this->model()::searchQuery($filter);
    }

    public function html(): HtmlBuilder
    {
        $configuration = [
            'has_search' => true,
        ];

        return $this->getHtml($configuration)->parameters([
            'responsive' => false,
            'autoWidth' => false,
        ]);
    }
}
```

### Example 2: E-commerce Order Management

#### Order Model

```php
<?php

namespace App\Models;

use HossamStandard\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use Searchable;

    protected $fillable = [
        'order_number',
        'customer_id',
        'total_amount',
        'status',
        'shipping_address',
        'billing_address',
        'notes',
        'ordered_at',
        'shipped_at',
        'delivered_at'
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'ordered_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'total_amount' => 'decimal:2'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getStatusBadge()
    {
        return match($this->status) {
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'confirmed' => '<span class="badge bg-info">Confirmed</span>',
            'processing' => '<span class="badge bg-primary">Processing</span>',
            'shipped' => '<span class="badge bg-success">Shipped</span>',
            'delivered' => '<span class="badge bg-success">Delivered</span>',
            'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
            default => '<span class="badge bg-light">Unknown</span>'
        };
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function canBeShipped()
    {
        return $this->status === 'processing';
    }
}
```

#### Order Filter

```php
<?php

namespace App\Filters;

use HossamStandard\Filters\BaseFilter;
use Carbon\Carbon;

class OrderFilter extends BaseFilter
{
    public function status($value): void
    {
        if (is_array($value)) {
            $this->query->whereIn('orders.status', $value);
        } else {
            $this->query->where('orders.status', $value);
        }
    }

    public function customer($value): void
    {
        if (!$this->hasJoinedBefore('customers as customers')) {
            $this->query->leftJoin('customers as customers', 'orders.customer_id', '=', 'customers.id');
        }
        $this->query->where('customers.id', $value);
    }

    public function date_range($value): void
    {
        if (is_array($value) && isset($value['from'], $value['to'])) {
            $this->query->whereBetween('orders.ordered_at', [
                Carbon::parse($value['from'])->startOfDay(),
                Carbon::parse($value['to'])->endOfDay()
            ]);
        }
    }

    public function total_amount_range($value): void
    {
        if (is_array($value) && isset($value['min'], $value['max'])) {
            $this->query->whereBetween('orders.total_amount', [
                (float)$value['min'],
                (float)$value['max']
            ]);
        }
    }

    public function has_payments($value): void
    {
        if ($value) {
            $this->query->whereHas('payments');
        } else {
            $this->query->whereDoesntHave('payments');
        }
    }

    public function overdue($value): void
    {
        if ($value) {
            $this->query->where('orders.status', '!=', 'delivered')
                       ->where('orders.status', '!=', 'cancelled')
                       ->where('orders.ordered_at', '<', now()->subDays(7));
        }
    }

    public function prepareFiltersArray($filters)
    {
        // Default to show only non-cancelled orders
        if (!isset($filters['status'])) {
            $filters['status:not'] = 'cancelled';
        }

        // Transform quick date filters
        if (isset($filters['period'])) {
            switch ($filters['period']) {
                case 'today':
                    $filters['date_range'] = [
                        'from' => today(),
                        'to' => today()
                    ];
                    break;
                case 'week':
                    $filters['date_range'] = [
                        'from' => now()->startOfWeek(),
                        'to' => now()->endOfWeek()
                    ];
                    break;
                case 'month':
                    $filters['date_range'] = [
                        'from' => now()->startOfMonth(),
                        'to' => now()->endOfMonth()
                    ];
                    break;
            }
            unset($filters['period']);
        }

        return $filters;
    }

    public function allowedSearchFields(): array
    {
        return [
            'id',
            'order_number',
            'customer_id',
            'total_amount',
            'status',
            'ordered_at',
            'shipped_at',
            'delivered_at'
        ];
    }

    public function getWithGroups(): array
    {
        return [
            'basic' => ['customer'],
            'detailed' => ['customer', 'orderItems.product', 'payments'],
            'summary' => ['customer:id,name,email']
        ];
    }
}
```

#### Order Saving Service

```php
<?php

namespace App\Services\Saving;

use HossamStandard\Services\BaseSavingService;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Support\Str;

class OrderSavingService extends BaseSavingService
{
    public string $modelName = Order::class;

    public function prepareArray($params)
    {
        // Generate order number for new orders
        if (!isset($params['id'])) {
            $params['order_number'] = $this->generateOrderNumber();
            $params['ordered_at'] = now();
        }

        // Set status based on business rules
        if (!isset($params['id'])) {
            $params['status'] = OrderStatus::PENDING->value;
        }

        // Calculate total amount if order items provided
        if (isset($params['order_items']) && !isset($params['total_amount'])) {
            $params['total_amount'] = $this->calculateTotalAmount($params['order_items']);
        }

        // Set timestamps based on status changes
        if (isset($params['status'])) {
            switch ($params['status']) {
                case OrderStatus::SHIPPED->value:
                    if (!isset($params['shipped_at'])) {
                        $params['shipped_at'] = now();
                    }
                    break;
                case OrderStatus::DELIVERED->value:
                    if (!isset($params['delivered_at'])) {
                        $params['delivered_at'] = now();
                    }
                    break;
            }
        }

        return $params;
    }

    public function validate($params)
    {
        // Validate customer exists
        if (isset($params['customer_id'])) {
            $customer = \App\Models\Customer::find($params['customer_id']);
            if (!$customer) {
                throw new \Exception('Customer not found');
            }
            if (!$customer->is_active) {
                throw new \Exception('Customer account is not active');
            }
        }

        // Validate order items
        if (isset($params['order_items'])) {
            $this->validateOrderItems($params['order_items']);
        }

        // Business rules for status changes
        if (isset($params['id'])) {
            $existingOrder = Order::find($params['id']);

            if (isset($params['status'])) {
                // Validate status transition
                if (!$this->isValidStatusTransition($existingOrder->status, $params['status'])) {
                    throw new \Exception("Invalid status transition from {$existingOrder->status} to {$params['status']}");
                }

                // Check if order can be modified
                if (in_array($existingOrder->status, ['shipped', 'delivered'])) {
                    throw new \Exception('Cannot modify shipped or delivered orders');
                }
            }
        }

        // Check inventory availability
        if (isset($params['order_items'])) {
            $this->checkInventoryAvailability($params['order_items']);
        }
    }

    public function saveRelatedData($model, $params)
    {
        // Save order items
        if (isset($params['order_items'])) {
            app(OrderItemSavingService::class)->saveMany($params['order_items'], [
                'order_id' => $model->id
            ]);

            // Update inventory
            $this->updateInventory($params['order_items']);
        }

        // Process payment if payment data provided
        if (isset($params['payment'])) {
            $params['payment']['order_id'] = $model->id;
            app(PaymentSavingService::class)->saveAndCommit($params['payment']);
        }
    }

    public function afterSave($model, $params)
    {
        // Recalculate total if items changed
        if (isset($params['order_items'])) {
            $model->load('orderItems');
            $calculatedTotal = $model->orderItems->sum(function ($item) {
                return $item->quantity * $item->unit_price;
            });

            if ($model->total_amount != $calculatedTotal) {
                $model->total_amount = $calculatedTotal;
                $model->saveQuietly(); // OK to save calculated field
            }
        }
    }

    public function afterCommit($result, $params)
    {
        // Send order confirmation email
        if (!isset($params['id'])) {
            \App\Jobs\SendOrderConfirmation::dispatch($result);
        }

        // Send shipping notification
        if (isset($params['status']) && $params['status'] === OrderStatus::SHIPPED->value) {
            \App\Jobs\SendShippingNotification::dispatch($result);
        }

        // Log activity
        activity()
            ->performedOn($result)
            ->causedBy(auth()->user())
            ->withProperties(['changes' => $params])
            ->log(isset($params['id']) ? 'Order updated' : 'Order created');
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }

    private function calculateTotalAmount(array $orderItems): float
    {
        $total = 0;
        foreach ($orderItems as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            $total += ($product->price ?? 0) * ($item['quantity'] ?? 0);
        }
        return $total;
    }

    private function validateOrderItems(array $orderItems): void
    {
        if (empty($orderItems)) {
            throw new \Exception('Order must contain at least one item');
        }

        foreach ($orderItems as $item) {
            if (!isset($item['product_id']) || !isset($item['quantity'])) {
                throw new \Exception('Product ID and quantity are required for each order item');
            }

            $product = \App\Models\Product::find($item['product_id']);
            if (!$product) {
                throw new \Exception("Product {$item['product_id']} not found");
            }

            if (!$product->is_active) {
                throw new \Exception("Product {$product->name} is not available");
            }

            if ($item['quantity'] <= 0) {
                throw new \Exception('Quantity must be greater than 0');
            }
        }
    }

    private function checkInventoryAvailability(array $orderItems): void
    {
        foreach ($orderItems as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            if ($product->stock_quantity < $item['quantity']) {
                throw new \Exception("Insufficient stock for product: {$product->name}");
            }
        }
    }

    private function updateInventory(array $orderItems): void
    {
        foreach ($orderItems as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            $product->decrement('stock_quantity', $item['quantity']);
        }
    }

    private function isValidStatusTransition(string $from, string $to): bool
    {
        $validTransitions = [
            OrderStatus::PENDING->value => [OrderStatus::CONFIRMED->value, OrderStatus::CANCELLED->value],
            OrderStatus::CONFIRMED->value => [OrderStatus::PROCESSING->value, OrderStatus::CANCELLED->value],
            OrderStatus::PROCESSING->value => [OrderStatus::SHIPPED->value, OrderStatus::CANCELLED->value],
            OrderStatus::SHIPPED->value => [OrderStatus::DELIVERED->value],
            OrderStatus::DELIVERED->value => [], // Final status
            OrderStatus::CANCELLED->value => [] // Final status
        ];

        return in_array($to, $validTransitions[$from] ?? []);
    }
}
```

### Example 3: Advanced Search Queries

```php
// Complex user search with multiple filters
$users = User::search([
    'filter' => [
        'status' => 'active',
        'role' => [1, 2, 3], // Multiple roles
        'department' => 5,
        'has_posts' => true,
        'verified' => true,
        'last_login' => 'week',
        'created_at:gte' => '2024-01-01',
        'name:like' => 'John'
    ],
    'with' => ['role', 'department', 'posts'],
    'sort' => '-created_at',
    'limit' => 50
]);

// Orders with date range and customer filters
$orders = Order::search([
    'filter' => [
        'status:in' => ['pending', 'confirmed', 'processing'],
        'customer' => [
            'country' => 'US',
            'status' => 'active'
        ],
        'date_range' => [
            'from' => '2024-01-01',
            'to' => '2024-12-31'
        ],
        'total_amount:gte' => 100
    ],
    'with_group' => 'detailed',
    'sort' => '-ordered_at'
]);

// Search with custom query builder for pagination
$query = User::searchQuery([
    'filter' => ['status' => 'active'],
    'with' => ['posts']
]);

$paginatedUsers = $query->paginate(20);

// Count queries
$activeUsersCount = User::searchCount(['filter' => ['status' => 'active']]);
$overdueOrdersCount = Order::searchCount(['filter' => ['overdue' => true]]);
```

### Example 4: Bulk Operations

```php
// Bulk user creation
$userData = [
    ['name' => 'User 1', 'email' => 'user1@example.com'],
    ['name' => 'User 2', 'email' => 'user2@example.com'],
    // ... more users
];

app(UserSavingService::class)->saveMany($userData, [
    'status' => 'active',
    'role_id' => 2
]);

// Bulk update with transaction
$userUpdates = [
    ['id' => 1, 'status' => 'inactive'],
    ['id' => 2, 'department_id' => 3],
    // ... more updates
];

\DB::transaction(function () use ($userUpdates) {
    foreach ($userUpdates as $update) {
        app(UserSavingService::class)->saveAndCommit($update);
    }
});

// Fast bulk insert (no relationships)
$logData = [
    ['action' => 'login', 'user_id' => 1, 'ip' => '192.168.1.1'],
    ['action' => 'logout', 'user_id' => 1, 'ip' => '192.168.1.1'],
    // ... more logs
];

app(ActivityLogSavingService::class)->storeManyFast($logData, [
    'created_at' => now(),
    'updated_at' => now()
]);
```

### Example 5: Controller Patterns

```php
<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Services\Saving\UserSavingService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserApiController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only([
            'status', 'role_id', 'department_id', 'q', 'created_from', 'created_to'
        ]);

        $params = [
            'filter' => $filters,
            'with' => ['role', 'department'],
            'sort' => $request->input('sort', '-created_at'),
            'limit' => $request->input('limit', 20)
        ];

        if ($request->has('page')) {
            $query = User::searchQuery($params);
            return response()->json($query->paginate());
        }

        $users = User::search($params);
        return response()->json(['data' => $users]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id'
        ]);

        try {
            $user = app(UserSavingService::class)->saveAndCommit($validated);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'User created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id'
        ]);

        $validated['id'] = $user->id;

        try {
            $updatedUser = app(UserSavingService::class)->saveAndCommit($validated);

            return response()->json([
                'success' => true,
                'data' => $updatedUser,
                'message' => 'User updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'status' => 'sometimes|in:active,inactive,suspended',
            'department_id' => 'sometimes|exists:departments,id'
        ]);

        $updatedUsers = [];
        \DB::transaction(function () use ($validated, &$updatedUsers) {
            foreach ($validated['user_ids'] as $userId) {
                $updateData = array_merge($validated, ['id' => $userId]);
                unset($updateData['user_ids']); // Remove array from individual updates

                $user = app(UserSavingService::class)->saveAndCommit($updateData);
                $updatedUsers[] = $user;
            }
        });

        return response()->json([
            'success' => true,
            'data' => $updatedUsers,
            'message' => count($updatedUsers) . ' users updated successfully'
        ]);
    }
}
```

## 📋 Migration Checklist

When migrating existing code to Hossam Standard:

### ✅ Phase 1: Models
- [ ] Add `Searchable` trait to all models
- [ ] Verify relationships are properly defined

### ✅ Phase 2: Filters
- [ ] Create filter classes for complex models
- [ ] Implement `allowedSearchFields()` for security
- [ ] Add custom filter methods as needed

### ✅ Phase 3: Services
- [ ] Create saving services extending `BaseSavingService`
- [ ] Implement all required methods (`prepareArray`, `validate`, etc.)
- [ ] Move business logic from controllers to services
- [ ] Use `saveAndCommit()` for all data operations

### ✅ Phase 4: Controllers
- [ ] Replace direct model operations with service calls
- [ ] Replace raw Eloquent queries with `search()` methods
- [ ] Simplify controller methods
- [ ] Add proper error handling

### ✅ Phase 5: DataTables
- [ ] Update `query()` methods to use `searchQuery()`
- [ ] Ensure filters work with new system

### ✅ Phase 6: Testing
- [ ] Update existing tests
- [ ] Add tests for saving services
- [ ] Test search functionality
- [ ] Verify transactions work correctly

### ✅ Phase 7: Performance
- [ ] Check query performance
- [ ] Optimize with proper eager loading
- [ ] Use `with_group` for related data
- [ ] Implement caching where appropriate

Remember: **Start small** - migrate one model/feature at a time, test thoroughly, then move to the next.
