@extends('layouts.app')

@section('title', 'لوحة المهام - E7lal.com')

@section('styles')
<style>
    .page-header {
        padding: 100px 0 40px;
    }
    
    .kanban-container {
        padding: 40px 0;
    }
    
    .kanban-board-wrapper {
        overflow-x: auto;
        overflow-y: hidden;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: var(--primary-color) #f0f0f0;
    }
    
    .kanban-board-wrapper::-webkit-scrollbar {
        height: 8px;
    }
    
    .kanban-board-wrapper::-webkit-scrollbar-track {
        background: #f0f0f0;
        border-radius: 10px;
    }
    
    .kanban-board-wrapper::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 10px;
    }
    
    .kanban-board {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        padding-bottom: 10px;
        min-width: min-content;
    }
    
    .kanban-column {
        background: #fff;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        min-height: 500px;
        flex-shrink: 0;
        scroll-snap-align: start;
    }
    
    @media (min-width: 768px) {
        .kanban-column {
            width: 320px;
        }
    }
    
    @media (max-width: 767px) {
        .kanban-column {
            width: 80vw;
        }
        
        .kanban-board-wrapper {
            margin: 0 -20px;
            padding: 0 20px;
        }
    }
    
    .kanban-column-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .kanban-column-title {
        font-weight: 700;
        font-size: 1.2rem;
        color: var(--dark-color);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .kanban-column-subtitle {
        font-size: 0.9rem;
        color: #666;
        font-weight: 500;
    }
    
    .kanban-column-count {
        background: var(--primary-color);
        color: #fff;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .task-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 12px;
        cursor: move;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .task-card:hover {
        background: #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .task-card.sortable-chosen {
        opacity: 0.5;
    }
    
    .task-card.sortable-ghost {
        opacity: 0.3;
        background: var(--accent-color);
    }
    
    .task-card.sortable-drag {
        opacity: 1;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        transform: rotate(3deg);
    }
    
    .task-title {
        font-weight: 600;
        font-size: 1rem;
        color: var(--dark-color);
        margin-bottom: 8px;
    }
    
    .task-description {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.5;
    }
    
    .share-section {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: #fff;
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 30px;
    }
    
    .share-input-group {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }
    
    .share-input {
        flex: 1;
        padding: 12px 20px;
        border-radius: 50px;
        border: none;
        font-family: 'Cairo', sans-serif;
        direction: ltr;
        text-align: left;
    }
    
    .btn-copy {
        background: #fff;
        color: var(--primary-color);
        padding: 12px 30px;
        border-radius: 50px;
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-copy:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .add-column-btn {
        background: linear-gradient(135deg, #e0e0e0, #f5f5f5);
        border: 3px dashed #ccc;
        border-radius: 15px;
        padding: 20px;
        min-height: 500px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
        width: 320px;
        scroll-snap-align: start;
    }
    
    @media (max-width: 767px) {
        .add-column-btn {
            width: 80vw;
        }
    }
    
    .add-column-btn:hover {
        background: linear-gradient(135deg, #d0d0d0, #e5e5e5);
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }
    
    .add-column-btn i {
        font-size: 4rem;
        color: #999;
        margin-bottom: 15px;
    }
    
    .add-column-btn span {
        font-size: 1.2rem;
        color: #666;
        font-weight: 600;
    }
    
    .column-actions {
        display: flex;
        gap: 5px;
        align-items: center;
    }
    
    .column-action-btn {
        background: none;
        border: none;
        color: #999;
        cursor: pointer;
        padding: 5px;
        font-size: 1rem;
        transition: color 0.3s;
    }
    
    .column-action-btn:hover {
        color: var(--primary-color);
    }
    
    .add-task-btn {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: #fff;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 10px;
    }
    
    .add-task-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    @media (max-width: 767px) {
        .share-input-group {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="text-center mb-3">لوحة المهام</h1>
        <p class="text-center">Task Board</p>
    </div>
</div>

<div class="kanban-container">
    <div class="container">
        <div class="share-section">
            <h5 class="mb-2">
                <i class="bi bi-share me-2"></i>
                شارك هذه اللوحة
            </h5>
            <p class="mb-0 opacity-75">انسخ الرابط أدناه لمشاركة هذه اللوحة مع الآخرين</p>
            <div class="share-input-group">
                <input type="text" class="share-input" id="shareUrl" value="{{ $board->share_url }}" readonly>
                <button class="btn btn-copy" onclick="copyShareLink()">
                    <i class="bi bi-clipboard me-2"></i>
                    نسخ الرابط
                </button>
            </div>
        </div>

        <div class="kanban-board-wrapper">
            <div class="kanban-board" id="kanban-board">
                @foreach($board->columns as $column)
                    <div class="kanban-column" data-column-id="{{ $column->id }}">
                        <div class="kanban-column-header">
                            <div>
                                <div class="kanban-column-title">
                                    <span class="column-name">{{ $column->name }}</span>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div class="kanban-column-count" id="count-{{ $column->id }}">{{ $column->tasks->count() }}</div>
                                @if($isAdmin)
                                    <div class="column-actions">
                                        <button class="column-action-btn" onclick="editColumn({{ $column->id }}, '{{ addslashes($column->name) }}')" title="تعديل">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="column-action-btn" onclick="deleteColumn({{ $column->id }})" title="حذف">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="kanban-tasks" id="column-{{ $column->id }}" data-column-id="{{ $column->id }}">
                            @foreach($column->tasks as $task)
                                <div class="task-card" data-id="{{ $task->id }}">
                                    <div class="task-title">{{ $task->title }}</div>
                                    @if($task->description)
                                        <div class="task-description">{{ $task->description }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @if($isAdmin)
                            <button class="add-task-btn" onclick="addTask({{ $column->id }})">
                                <i class="bi bi-plus-circle me-2"></i>
                                إضافة مهمة
                            </button>
                        @endif
                    </div>
                @endforeach
                
                @if($isAdmin)
                    <div class="add-column-btn" onclick="addColumn()">
                        <i class="bi bi-plus-circle"></i>
                        <span>إضافة عمود</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    const csrfToken = '{{ csrf_token() }}';
    const boardId = {{ $board->id }};
    const isAdmin = {{ $isAdmin ? 'true' : 'false' }};
    
    function copyShareLink() {
        const input = document.getElementById('shareUrl');
        input.select();
        document.execCommand('copy');
        
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check2 me-2"></i>تم النسخ!';
        
        setTimeout(() => {
            btn.innerHTML = originalText;
        }, 2000);
    }
    
    function updateTaskPosition(taskId, newColumnId, newPosition) {
        fetch(`/tasks/${taskId}/update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                column_id: newColumnId,
                position: newPosition,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Failed to update task:', data.message);
                alert(data.message || 'فشل في تحديث المهمة');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error updating task:', error);
            alert('حدث خطأ في تحديث المهمة');
            location.reload();
        });
    }
    
    function updateColumnCounts() {
        document.querySelectorAll('.kanban-column').forEach(column => {
            const columnId = column.getAttribute('data-column-id');
            const count = column.querySelectorAll('.task-card').length;
            const countElement = document.getElementById(`count-${columnId}`);
            if (countElement) {
                countElement.textContent = count;
            }
        });
    }
    
    function addColumn() {
        const name = prompt('اسم العمود الجديد:');
        if (!name) return;
        
        fetch(`/boards/${boardId}/columns`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ name }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'فشل في إضافة العمود');
            }
        })
        .catch(error => {
            console.error('Error adding column:', error);
            alert('حدث خطأ في إضافة العمود');
        });
    }
    
    function editColumn(columnId, currentName) {
        const name = prompt('تعديل اسم العمود:', currentName);
        if (!name || name === currentName) return;
        
        fetch(`/columns/${columnId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ name }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'فشل في تعديل العمود');
            }
        })
        .catch(error => {
            console.error('Error updating column:', error);
            alert('حدث خطأ في تعديل العمود');
        });
    }
    
    function deleteColumn(columnId) {
        if (!confirm('هل أنت متأكد من حذف هذا العمود؟ لا يمكن حذف عمود يحتوي على مهام.')) return;
        
        fetch(`/columns/${columnId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'فشل في حذف العمود');
            }
        })
        .catch(error => {
            console.error('Error deleting column:', error);
            alert('حدث خطأ في حذف العمود');
        });
    }
    
    function addTask(columnId) {
        const title = prompt('عنوان المهمة:');
        if (!title) return;
        
        const description = prompt('وصف المهمة (اختياري):');
        
        fetch(`/columns/${columnId}/tasks`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ title, description }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'فشل في إضافة المهمة');
            }
        })
        .catch(error => {
            console.error('Error adding task:', error);
            alert('حدث خطأ في إضافة المهمة');
        });
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const columns = document.querySelectorAll('.kanban-tasks');
        
        columns.forEach(column => {
            new Sortable(column, {
                group: 'tasks',
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                touchStartThreshold: 5,
                forceFallback: true,
                scroll: true,
                scrollSensitivity: 100,
                scrollSpeed: 10,
                bubbleScroll: true,
                delay: 100,
                delayOnTouchOnly: true,
                onEnd: function(evt) {
                    const taskId = evt.item.getAttribute('data-id');
                    const newColumnId = evt.to.getAttribute('data-column-id');
                    const newPosition = evt.newIndex;
                    
                    updateTaskPosition(taskId, newColumnId, newPosition);
                    updateColumnCounts();
                },
            });
        });
    });
</script>
@endsection
