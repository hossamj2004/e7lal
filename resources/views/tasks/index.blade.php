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
    
    .kanban-board {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .kanban-column {
        background: #fff;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        min-height: 500px;
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
    
    .column-todo .kanban-column-header {
        border-bottom-color: #f093fb;
    }
    
    .column-todo .kanban-column-count {
        background: linear-gradient(135deg, #f093fb, #f5576c);
    }
    
    .column-in-progress .kanban-column-header {
        border-bottom-color: #4facfe;
    }
    
    .column-in-progress .kanban-column-count {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
    }
    
    .column-done .kanban-column-header {
        border-bottom-color: #43e97b;
    }
    
    .column-done .kanban-column-count {
        background: linear-gradient(135deg, #43e97b, #38f9d7);
    }
    
    @media (max-width: 992px) {
        .kanban-board {
            grid-template-columns: 1fr;
        }
        
        .kanban-column {
            min-height: 300px;
        }
        
        .share-input-group {
            flex-direction: column;
        }
    }
    
    @media (min-width: 768px) and (max-width: 992px) {
        .kanban-board {
            display: flex;
            overflow-x: auto;
            gap: 15px;
            padding-bottom: 20px;
        }
        
        .kanban-column {
            min-width: 300px;
            flex-shrink: 0;
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

        <div class="kanban-board">
            <div class="kanban-column column-todo">
                <div class="kanban-column-header">
                    <div>
                        <div class="kanban-column-title">
                            <span>To Do</span>
                        </div>
                        <div class="kanban-column-subtitle">لازم يتعمل</div>
                    </div>
                    <div class="kanban-column-count" id="count-todo">{{ $tasks['todo']->count() }}</div>
                </div>
                <div class="kanban-tasks" id="todo" data-status="todo">
                    @foreach($tasks['todo'] as $task)
                        <div class="task-card" data-id="{{ $task->id }}">
                            <div class="task-title">{{ $task->title }}</div>
                            @if($task->description)
                                <div class="task-description">{{ $task->description }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="kanban-column column-in-progress">
                <div class="kanban-column-header">
                    <div>
                        <div class="kanban-column-title">
                            <span>In Progress</span>
                        </div>
                        <div class="kanban-column-subtitle">شغال عليها</div>
                    </div>
                    <div class="kanban-column-count" id="count-in_progress">{{ $tasks['in_progress']->count() }}</div>
                </div>
                <div class="kanban-tasks" id="in_progress" data-status="in_progress">
                    @foreach($tasks['in_progress'] as $task)
                        <div class="task-card" data-id="{{ $task->id }}">
                            <div class="task-title">{{ $task->title }}</div>
                            @if($task->description)
                                <div class="task-description">{{ $task->description }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="kanban-column column-done">
                <div class="kanban-column-header">
                    <div>
                        <div class="kanban-column-title">
                            <span>Finished</span>
                        </div>
                        <div class="kanban-column-subtitle">خلصت</div>
                    </div>
                    <div class="kanban-column-count" id="count-done">{{ $tasks['done']->count() }}</div>
                </div>
                <div class="kanban-tasks" id="done" data-status="done">
                    @foreach($tasks['done'] as $task)
                        <div class="task-card" data-id="{{ $task->id }}">
                            <div class="task-title">{{ $task->title }}</div>
                            @if($task->description)
                                <div class="task-description">{{ $task->description }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    const csrfToken = '{{ csrf_token() }}';
    
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
    
    function updateTaskPosition(taskId, newStatus, newPosition) {
        fetch(`/tasks/${taskId}/update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                status: newStatus,
                position: newPosition,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Failed to update task:', data.message);
            }
        })
        .catch(error => {
            console.error('Error updating task:', error);
        });
    }
    
    function updateColumnCounts() {
        ['todo', 'in_progress', 'done'].forEach(status => {
            const column = document.getElementById(status);
            const count = column.querySelectorAll('.task-card').length;
            document.getElementById(`count-${status}`).textContent = count;
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
                onEnd: function(evt) {
                    const taskId = evt.item.getAttribute('data-id');
                    const newStatus = evt.to.getAttribute('data-status');
                    const newPosition = evt.newIndex;
                    
                    updateTaskPosition(taskId, newStatus, newPosition);
                    updateColumnCounts();
                },
            });
        });
    });
</script>
@endsection
