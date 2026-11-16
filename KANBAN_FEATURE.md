# Kanban Task Board Feature

## Overview
A full-featured Kanban-style task board for E7lal.com with drag-and-drop functionality, shareable boards, and Arabic RTL support.

## 🚀 Quick Start

### Public Access
- Visit `/tasks` to see the default task board
- No login required
- Drag tasks between columns to update their status
- Copy the share link to share with others

### Admin Access
1. Login as admin
2. Navigate to "إدارة المهام" in the sidebar
3. Create, edit, and delete tasks
4. Manage multiple boards

## 📋 Features

### Public Task Board (`/tasks`)
- **Three Columns:**
  - To Do (لازم يتعمل) - Tasks waiting to be started
  - In Progress (شغال عليها) - Tasks currently being worked on
  - Finished (خلصت) - Completed tasks
  
- **Drag & Drop:**
  - Move tasks between columns
  - Reorder within columns
  - Works on desktop and mobile (touch-enabled)
  - Changes saved automatically via AJAX

- **Shareable:**
  - Each board has a unique share link
  - Click "نسخ الرابط" to copy the link
  - Anyone with the link can view and move tasks
  - No login required

### Admin Management

#### Task Management (`/admin/tasks`)
- **List View:** See all tasks across all boards
- **Create Task:**
  - Required: Title, Board, Status
  - Optional: Description
  - Tasks automatically positioned at the end of the chosen column
  
- **Edit Task:**
  - Modify title, description, status
  - Move to different board
  - View task metadata (created/updated dates)
  
- **Delete Task:**
  - Confirmation required
  - Permanent deletion

#### Board Management (`/admin/boards`)
- **List View:** See all boards with task counts and share links
- **Create Board:**
  - Custom name
  - Automatically gets unique share token
  
- **Default Board:**
  - One board marked as default (created by migration)
  - Shown at `/tasks` route
  - Cannot be deleted
  
- **Delete Board:**
  - Non-default boards can be deleted
  - Cascades to delete all tasks on the board
  - Confirmation required

## 🗄️ Database Structure

### Tables

#### `boards`
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | varchar | Board name |
| share_token | varchar(64) | Unique share URL token |
| is_default | boolean | Is this the default board? |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

#### `tasks`
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| board_id | bigint | Foreign key to boards |
| title | varchar | Task title |
| description | text | Task description (nullable) |
| status | enum | todo, in_progress, or done |
| position | integer | Position within status column |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Indexes:**
- `board_id, status, position` - For efficient sorting and filtering

## 🔗 Routes

### Public Routes (No Authentication)
```php
GET  /tasks              // Default board view
GET  /tasks/{token}      // Shareable board view
POST /tasks/{task}/update // Update task (AJAX)
```

### Admin Routes (Auth + Admin Middleware)
```php
// Task Management
GET    /admin/tasks              // List all tasks
GET    /admin/tasks/create       // Create form
POST   /admin/tasks              // Store task
GET    /admin/tasks/{task}/edit  // Edit form
PUT    /admin/tasks/{task}       // Update task
DELETE /admin/tasks/{task}       // Delete task

// Board Management
GET    /admin/boards             // List boards
GET    /admin/boards/create      // Create form
POST   /admin/boards             // Store board
DELETE /admin/boards/{board}     // Delete board
```

## 🛡️ Security

### CSRF Protection
All POST/PUT/DELETE requests require CSRF token:
```javascript
headers: {
    'X-CSRF-TOKEN': csrfToken,
}
```

### Status Validation
The update endpoint validates status values:
```php
'status' => 'required|in:todo,in_progress,done'
```

### Admin Protection
All admin routes use the existing `AdminMiddleware`:
```php
Route::prefix('admin')->middleware(['auth'])->group(...)
```

### Share Token Security
- 32 random characters
- Unique in database
- Unguessable (no sequential IDs)

## 📱 Mobile Support

### Responsive Design
- **Desktop (>992px):** Three columns side by side
- **Tablet (768px-992px):** Horizontal scrolling columns
- **Mobile (<768px):** Stacked columns

### Touch Drag & Drop
```javascript
new Sortable(column, {
    touchStartThreshold: 5,
    forceFallback: true,  // Ensures mobile compatibility
    // ... other options
});
```

## 🎨 Styling

### CSS Variables
```css
--primary-color: #1a5f7a;
--secondary-color: #159895;
--accent-color: #57c5b6;
--dark-color: #0b2447;
```

### Column Colors
- **To Do:** Pink gradient (#f093fb → #f5576c)
- **In Progress:** Blue gradient (#4facfe → #00f2fe)
- **Finished:** Green gradient (#43e97b → #38f9d7)

### Animations
- Hover effects on task cards
- Smooth drag transitions
- Visual feedback on drag operations
- Copy button confirmation

## 🧪 Testing

### Run Tests
```bash
php artisan test --filter TaskBoardTest
```

### Test Coverage
- Route accessibility (public and shareable)
- Task update functionality
- Status validation
- AJAX response format

### Test Factories
```php
// Create a board
$board = Board::factory()->create();
$defaultBoard = Board::factory()->default()->create();

// Create tasks
$task = Task::factory()->create();
$todoTask = Task::factory()->todo()->create();
```

## 🚀 Deployment

### Standard Deployment (No Build Required)
```bash
# 1. Pull latest code
git pull

# 2. Run migrations
php artisan migrate --force

# 3. Clear caches (optional)
php artisan route:clear
php artisan view:clear
php artisan config:clear
```

### What Happens on Migration
1. Creates `boards` table
2. Creates `tasks` table
3. Seeds default board with 4 example tasks:
   - "مرحباً بك في لوحة المهام" (To Do)
   - "تجربة السحب والإفلات" (To Do)
   - "مهمة قيد التنفيذ" (In Progress)
   - "مهمة مكتملة" (Done)

### No Build Step Needed
All JavaScript dependencies loaded from CDN:
- SortableJS 1.15.0
- Bootstrap 5.3.2
- Bootstrap Icons

## 💡 Usage Examples

### For Site Visitors
1. Visit `/tasks`
2. See example tasks in different columns
3. Try dragging a task from "To Do" to "In Progress"
4. Task automatically saves to database
5. Copy share link to send to team members

### For Admins
1. Login to admin panel
2. Click "إدارة المهام" in sidebar
3. Click "إضافة مهمة جديدة"
4. Fill in task details:
   - Board: Default Board
   - Title: "تحديث الموقع"
   - Description: "تحديث الصفحة الرئيسية"
   - Status: To Do
5. Click "حفظ المهمة"
6. Task appears on public board immediately

### Creating a New Board
1. Go to `/admin/boards`
2. Click "إضافة لوحة جديدة"
3. Enter name: "مشروع التسويق"
4. Click "حفظ اللوحة"
5. Board gets unique share link
6. Copy and share with your marketing team

## 🔧 Customization

### Adding More Statuses
1. Update migration enum:
```php
$table->enum('status', ['todo', 'in_progress', 'done', 'blocked']);
```

2. Add validation:
```php
'status' => 'required|in:todo,in_progress,done,blocked'
```

3. Add column to view:
```blade
<div class="kanban-column column-blocked">
    <!-- ... -->
</div>
```

### Changing Colors
Edit CSS in `resources/views/tasks/index.blade.php`:
```css
.column-todo .kanban-column-count {
    background: linear-gradient(135deg, #YOUR_COLOR_1, #YOUR_COLOR_2);
}
```

### Adding Assignees
1. Add migration:
```php
$table->foreignId('user_id')->nullable()->constrained();
```

2. Update Task model:
```php
public function user() {
    return $this->belongsTo(User::class);
}
```

3. Add to views and forms

## 📊 Performance

### Database Indexes
Composite index on `(board_id, status, position)` ensures:
- Fast column queries
- Efficient sorting
- Quick position updates

### AJAX Updates
- Only sends changed fields
- No page reload required
- Optimistic UI updates

### Position Management
Efficient position algorithm:
- Only updates affected tasks
- Uses database transactions
- Prevents race conditions

## 🐛 Troubleshooting

### Tasks not saving
- Check browser console for JavaScript errors
- Verify CSRF token is present
- Check network tab for failed requests

### Drag and drop not working
- Ensure SortableJS loaded (check network tab)
- Check for JavaScript console errors
- Test on different browsers

### Share link not working
- Verify board exists in database
- Check share_token is unique
- Ensure route is registered

### Mobile drag issues
- Confirm `forceFallback: true` in Sortable options
- Test touch events work on device
- Check viewport meta tag

## 📝 Code Examples

### Creating a Task Programmatically
```php
use App\Models\Board;
use App\Models\Task;

$board = Board::where('is_default', true)->first();

Task::create([
    'board_id' => $board->id,
    'title' => 'New Task',
    'description' => 'Task description',
    'status' => 'todo',
    'position' => Task::where('board_id', $board->id)
        ->where('status', 'todo')
        ->max('position') + 1,
]);
```

### Fetching Tasks by Status
```php
$board = Board::find(1);
$todoTasks = $board->tasks()->byStatus('todo')->get();
$inProgressTasks = $board->tasks()->byStatus('in_progress')->get();
$doneTasks = $board->tasks()->byStatus('done')->get();
```

### Updating Task Position
```php
$task = Task::find(1);
$task->update([
    'status' => 'in_progress',
    'position' => 0,
]);
```

## 🎯 Future Enhancements

Possible improvements:
- [ ] Task assignments to users
- [ ] Task due dates and reminders
- [ ] Task labels/tags with colors
- [ ] Task comments/notes
- [ ] Activity history/audit log
- [ ] Email notifications
- [ ] Archived tasks
- [ ] Task search and filters
- [ ] Bulk operations
- [ ] Task templates
- [ ] Time tracking
- [ ] File attachments

## 📞 Support

For issues or questions:
1. Check this documentation
2. Review the code comments
3. Check Laravel logs: `storage/logs/laravel.log`
4. Test with example data
5. Contact development team

## 📄 License

Same as E7lal.com project license.
