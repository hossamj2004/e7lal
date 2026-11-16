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
        margin-bottom: 12px;
    }
    
    .task-actions {
        display: flex;
        gap: 8px;
        justify-content: center;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #e0e0e0;
    }
    
    .task-arrow-btn {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: #fff;
        border: none;
        border-radius: 8px;
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1.2rem;
    }
    
    .task-arrow-btn:hover:not(:disabled) {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .task-arrow-btn:active:not(:disabled) {
        transform: scale(0.95);
    }
    
    .task-arrow-btn:disabled {
        background: #e0e0e0;
        color: #999;
        cursor: not-allowed;
        opacity: 0.5;
    }
    
    .task-arrow-btn i {
        pointer-events: none;
    }
    
    .task-delete-btn {
        background: #dc3545;
        color: #fff;
        border: none;
        border-radius: 6px;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.75rem;
        flex-shrink: 0;
        margin-right: 8px;
    }
    
    .task-delete-btn:hover {
        background: #c82333;
        transform: scale(1.1);
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
    }
    
    .task-modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 9998;
        animation: fadeIn 0.2s;
    }
    
    .task-modal-overlay.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .task-modal {
        background: #fff;
        border-radius: 15px;
        padding: 30px;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        animation: slideIn 0.3s;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: scale(1);
        }
        to {
            opacity: 0;
            transform: scale(0.8);
        }
    }
    
    @keyframes slideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    .task-modal h4 {
        color: var(--dark-color);
        font-weight: 700;
        margin-bottom: 20px;
    }
    
    .task-modal label {
        display: block;
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 8px;
    }
    
    .task-modal input,
    .task-modal textarea {
        width: 100%;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-family: 'Cairo', sans-serif;
        font-size: 1rem;
        transition: border-color 0.3s;
        margin-bottom: 15px;
    }
    
    .task-modal input:focus,
    .task-modal textarea:focus {
        outline: none;
        border-color: var(--primary-color);
    }
    
    .task-modal textarea {
        resize: vertical;
        min-height: 80px;
    }
    
    .task-modal-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }
    
    .task-modal-actions button {
        flex: 1;
        padding: 12px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .task-modal-submit {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: #fff;
    }
    
    .task-modal-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .task-modal-cancel {
        background: #e0e0e0;
        color: #666;
    }
    
    .task-modal-cancel:hover {
        background: #d0d0d0;
    }
    
    .task-comment-btn {
        background: none;
        border: none;
        color: var(--primary-color);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 6px;
        transition: all 0.3s;
    }
    
    .task-comment-btn:hover {
        background: rgba(26, 95, 122, 0.1);
    }
    
    .comment-count-badge {
        background: var(--primary-color);
        color: #fff;
        font-size: 0.75rem;
        padding: 2px 6px;
        border-radius: 10px;
        font-weight: 600;
    }
    
    .task-blocked-badge {
        display: inline-block;
        background: linear-gradient(135deg, #ff6b6b, #ff8e53);
        color: #fff;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-top: 8px;
    }
    
    .task-detail-modal {
        max-width: 600px;
        max-height: 85vh;
        overflow-y: auto;
    }
    
    .task-detail-header {
        border-bottom: 2px solid #e0e0e0;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    
    .task-detail-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 10px;
    }
    
    .task-detail-description {
        color: #666;
        margin-bottom: 15px;
        line-height: 1.6;
    }
    
    .blocked-toggle {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    
    .blocked-toggle label {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        font-weight: 600;
    }
    
    .blocked-toggle input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }
    
    .blocked-reason-input {
        margin-top: 10px;
        display: none;
    }
    
    .blocked-reason-input.active {
        display: block;
    }
    
    .comments-section {
        border-top: 2px solid #e0e0e0;
        padding-top: 20px;
        margin-top: 20px;
    }
    
    .comments-header {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--dark-color);
        margin-bottom: 15px;
    }
    
    .comment-item {
        background: #f8f9fa;
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 10px;
        position: relative;
    }
    
    .comment-author {
        font-weight: 600;
        color: var(--primary-color);
        font-size: 0.9rem;
        margin-bottom: 5px;
    }
    
    .comment-body {
        color: #333;
        line-height: 1.5;
        margin-bottom: 5px;
    }
    
    .comment-time {
        color: #999;
        font-size: 0.8rem;
    }
    
    .comment-delete {
        position: absolute;
        top: 8px;
        left: 8px;
        background: #dc3545;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.7rem;
    }
    
    .comment-delete:hover {
        background: #c82333;
    }
    
    .add-comment-form {
        margin-top: 15px;
    }
    
    .add-comment-form textarea {
        width: 100%;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-family: 'Cairo', sans-serif;
        resize: vertical;
        min-height: 80px;
        margin-bottom: 10px;
    }
    
    .add-comment-form input {
        width: 100%;
        padding: 10px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-family: 'Cairo', sans-serif;
        margin-bottom: 10px;
    }
    
    .task-card-clickable {
        cursor: pointer;
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
                @foreach($board->columns as $index => $column)
                    <div class="kanban-column" data-column-id="{{ $column->id }}" data-column-index="{{ $index }}">
                        <div class="kanban-column-header">
                            <div>
                                <div class="kanban-column-title">
                                    <span class="column-name">{{ $column->name }}</span>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div class="kanban-column-count" id="count-{{ $column->id }}">{{ $column->tasks->count() }}</div>
                                @if($canManage)
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
                                <div class="task-card task-card-clickable" data-id="{{ $task->id }}" data-column-id="{{ $column->id }}" onclick="event.stopPropagation(); openTaskDetail({{ $task->id }})">
                                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                                        <div class="task-title" style="margin-bottom: 0; flex: 1;">{{ $task->title }}</div>
                                        @if($canManage)
                                            <button class="task-delete-btn" onclick="event.stopPropagation(); deleteTask({{ $task->id }})" title="حذف المهمة">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        @endif
                                    </div>
                                    @if($task->description)
                                        <div class="task-description">{{ Str::limit($task->description, 100) }}</div>
                                    @endif
                                    
                                    @if($task->is_blocked)
                                        <div class="task-blocked-badge">
                                            🔴 واقفة: {{ Str::limit($task->blocked_reason, 50) }}
                                        </div>
                                    @endif
                                    
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                                        <button class="task-comment-btn" onclick="event.stopPropagation(); openTaskDetail({{ $task->id }})">
                                            <i class="bi bi-chat-dots"></i>
                                            @if($task->comments_count > 0)
                                                <span class="comment-count-badge">{{ $task->comments_count }}</span>
                                            @endif
                                        </button>
                                        
                                        <div class="task-actions" style="margin: 0;">
                                            <button class="task-arrow-btn task-arrow-right" 
                                                    onclick="event.stopPropagation(); moveTaskToColumn({{ $task->id }}, 'right')"
                                                    title="نقل لليمين"
                                                    {{ $index === 0 ? 'disabled' : '' }}>
                                                <i class="bi bi-arrow-right"></i>
                                            </button>
                                            <button class="task-arrow-btn task-arrow-left" 
                                                    onclick="event.stopPropagation(); moveTaskToColumn({{ $task->id }}, 'left')"
                                                    title="نقل لليسار"
                                                    {{ $index === count($board->columns) - 1 ? 'disabled' : '' }}>
                                                <i class="bi bi-arrow-left"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($canManage)
                            <button class="add-task-btn" onclick="showAddTaskModal({{ $column->id }})">
                                <i class="bi bi-plus-circle me-2"></i>
                                إضافة مهمة
                            </button>
                        @endif
                    </div>
                @endforeach
                
                @if($canManage)
                    <div class="add-column-btn" onclick="addColumn()">
                        <i class="bi bi-plus-circle"></i>
                        <span>إضافة عمود</span>
                    </div>
                @endif
            </div>
        </div>
        
        @if($canManage)
        <!-- Add Task Modal -->
        <div class="task-modal-overlay" id="taskModal">
            <div class="task-modal">
                <h4>إضافة مهمة جديدة</h4>
                <form id="addTaskForm">
                    <div>
                        <label for="taskTitle">عنوان المهمة <span style="color: #dc3545;">*</span></label>
                        <input type="text" id="taskTitle" required placeholder="أدخل عنوان المهمة">
                    </div>
                    <div>
                        <label for="taskDescription">الوصف (اختياري)</label>
                        <textarea id="taskDescription" placeholder="أدخل وصف المهمة"></textarea>
                    </div>
                    <div class="task-modal-actions">
                        <button type="submit" class="task-modal-submit">
                            <i class="bi bi-check-circle me-2"></i>
                            إضافة
                        </button>
                        <button type="button" class="task-modal-cancel" onclick="closeTaskModal()">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
        
        <!-- Task Detail Modal -->
        <div class="task-modal-overlay" id="taskDetailModal">
            <div class="task-modal task-detail-modal">
                <div class="task-detail-header">
                    <h4 class="task-detail-title" id="detailTaskTitle"></h4>
                    <p class="task-detail-description" id="detailTaskDescription"></p>
                </div>
                
                <!-- Blocked Status Toggle -->
                <div class="blocked-toggle">
                    <label>
                        <input type="checkbox" id="blockedCheckbox" onchange="toggleBlockedReason()">
                        <span>🔴 التاسك واقفة</span>
                    </label>
                    <div class="blocked-reason-input" id="blockedReasonContainer">
                        <textarea id="blockedReasonText" placeholder="واقفة على إيه؟" rows="2"></textarea>
                        <button type="button" onclick="updateBlockedStatus()" style="background: var(--primary-color); color: #fff; padding: 8px 16px; border: none; border-radius: 8px; cursor: pointer; width: 100%; font-family: 'Cairo', sans-serif; font-weight: 600;">
                            حفظ الحالة
                        </button>
                    </div>
                </div>
                
                <!-- Comments Section -->
                <div class="comments-section">
                    <div class="comments-header">
                        💬 التعليقات
                    </div>
                    <div id="commentsList"></div>
                    
                    <!-- Add Comment Form -->
                    <div class="add-comment-form">
                        <textarea id="commentBody" placeholder="أضف تعليق..." maxlength="2000"></textarea>
                        <input type="text" id="commentAuthor" placeholder="اسمك (اختياري)" maxlength="100">
                        <button type="button" onclick="addComment()" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: #fff; padding: 12px; border: none; border-radius: 8px; cursor: pointer; width: 100%; font-family: 'Cairo', sans-serif; font-weight: 600;">
                            <i class="bi bi-send me-2"></i>
                            إضافة تعليق
                        </button>
                    </div>
                </div>
                
                <div class="task-modal-actions" style="margin-top: 20px;">
                    <button type="button" class="task-modal-cancel" onclick="closeTaskDetail()" style="width: 100%;">
                        إغلاق
                    </button>
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
    const boardId = {{ $board->id }};
    const canManage = {{ $canManage ? 'true' : 'false' }};
    let currentColumnId = null;
    let currentTaskId = null;
    
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
    
    function showAddTaskModal(columnId) {
        currentColumnId = columnId;
        document.getElementById('taskModal').classList.add('active');
        document.getElementById('taskTitle').focus();
    }
    
    function closeTaskModal() {
        document.getElementById('taskModal').classList.remove('active');
        document.getElementById('addTaskForm').reset();
        currentColumnId = null;
    }
    
    
    document.getElementById('addTaskForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const title = document.getElementById('taskTitle').value.trim();
        const description = document.getElementById('taskDescription').value.trim();
        
        if (!title) {
            alert('يرجى إدخال عنوان المهمة');
            return;
        }
        
        fetch(`/columns/${currentColumnId}/tasks`, {
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
    });
    
    function deleteTask(taskId) {
        if (!confirm('هل أنت متأكد من حذف هذه المهمة؟')) return;
        
        fetch(`/tasks/${taskId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const taskCard = document.querySelector(`.task-card[data-id="${taskId}"]`);
                if (taskCard) {
                    taskCard.style.animation = 'fadeOut 0.3s';
                    setTimeout(() => {
                        taskCard.remove();
                        updateColumnCounts();
                    }, 300);
                }
            } else {
                alert(data.message || 'فشل في حذف المهمة');
            }
        })
        .catch(error => {
            console.error('Error deleting task:', error);
            alert('حدث خطأ في حذف المهمة');
        });
    }
    
    function moveTaskToColumn(taskId, direction) {
        const taskCard = document.querySelector(`.task-card[data-id="${taskId}"]`);
        if (!taskCard) return;
        
        const currentColumn = taskCard.closest('.kanban-column');
        const currentColumnIndex = parseInt(currentColumn.getAttribute('data-column-index'));
        
        // Get all columns
        const allColumns = Array.from(document.querySelectorAll('.kanban-column[data-column-id]'));
        
        // Calculate target column index based on direction
        // Right arrow = previous column in DOM (RTL, so visually right)
        // Left arrow = next column in DOM (RTL, so visually left)
        let targetColumnIndex;
        if (direction === 'right') {
            targetColumnIndex = currentColumnIndex - 1;
        } else {
            targetColumnIndex = currentColumnIndex + 1;
        }
        
        // Validate target exists
        if (targetColumnIndex < 0 || targetColumnIndex >= allColumns.length) {
            return;
        }
        
        const targetColumn = allColumns[targetColumnIndex];
        const targetColumnId = targetColumn.getAttribute('data-column-id');
        const targetTasksContainer = targetColumn.querySelector('.kanban-tasks');
        
        // Calculate position (append to end)
        const targetPosition = targetTasksContainer.querySelectorAll('.task-card').length;
        
        // Disable buttons during move
        const buttons = taskCard.querySelectorAll('.task-arrow-btn');
        buttons.forEach(btn => btn.disabled = true);
        
        // Call the update endpoint
        fetch(`/tasks/${taskId}/update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                column_id: targetColumnId,
                position: targetPosition,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Move card in DOM
                taskCard.setAttribute('data-column-id', targetColumnId);
                targetTasksContainer.appendChild(taskCard);
                
                // Update arrow button states
                updateArrowButtons(taskCard, targetColumnIndex, allColumns.length);
                
                // Re-enable buttons
                buttons.forEach(btn => btn.disabled = false);
                
                // Update column counts
                updateColumnCounts();
                
                // Visual feedback
                taskCard.style.animation = 'none';
                setTimeout(() => {
                    taskCard.style.animation = 'pulse 0.5s ease';
                }, 10);
            } else {
                alert(data.message || 'فشل في نقل المهمة');
                buttons.forEach(btn => btn.disabled = false);
            }
        })
        .catch(error => {
            console.error('Error moving task:', error);
            alert('حدث خطأ في نقل المهمة');
            buttons.forEach(btn => btn.disabled = false);
        });
    }
    
    function updateArrowButtons(taskCard, columnIndex, totalColumns) {
        const rightBtn = taskCard.querySelector('.task-arrow-right');
        const leftBtn = taskCard.querySelector('.task-arrow-left');
        
        if (rightBtn) {
            rightBtn.disabled = (columnIndex === 0);
        }
        if (leftBtn) {
            leftBtn.disabled = (columnIndex === totalColumns - 1);
        }
    }
    
    function openTaskDetail(taskId) {
        currentTaskId = taskId;
        
        fetch(`/tasks/${taskId}/comments`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('detailTaskTitle').textContent = data.task.title;
                    document.getElementById('detailTaskDescription').textContent = data.task.description || '';
                    
                    // Set blocked status
                    document.getElementById('blockedCheckbox').checked = data.task.is_blocked;
                    document.getElementById('blockedReasonText').value = data.task.blocked_reason || '';
                    if (data.task.is_blocked) {
                        document.getElementById('blockedReasonContainer').classList.add('active');
                    } else {
                        document.getElementById('blockedReasonContainer').classList.remove('active');
                    }
                    
                    // Render comments
                    renderComments(data.comments);
                    
                    // Show modal
                    document.getElementById('taskDetailModal').classList.add('active');
                }
            })
            .catch(error => {
                console.error('Error loading task:', error);
                alert('حدث خطأ في تحميل التاسك');
            });
    }
    
    function closeTaskDetail() {
        document.getElementById('taskDetailModal').classList.remove('active');
        document.getElementById('commentBody').value = '';
        document.getElementById('commentAuthor').value = '';
        currentTaskId = null;
    }
    
    function toggleBlockedReason() {
        const checkbox = document.getElementById('blockedCheckbox');
        const container = document.getElementById('blockedReasonContainer');
        
        if (checkbox.checked) {
            container.classList.add('active');
        } else {
            container.classList.remove('active');
            updateBlockedStatus();
        }
    }
    
    function updateBlockedStatus() {
        const isBlocked = document.getElementById('blockedCheckbox').checked;
        const reason = document.getElementById('blockedReasonText').value.trim();
        const authorName = document.getElementById('commentAuthor').value.trim();
        
        if (isBlocked && !reason) {
            alert('يرجى إدخال سبب التوقف');
            return;
        }
        
        fetch(`/tasks/${currentTaskId}/blocked`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                is_blocked: isBlocked,
                blocked_reason: reason,
                author_name: authorName,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload the page to show updated badge
                location.reload();
            } else {
                alert(data.message || 'فشل في تحديث الحالة');
            }
        })
        .catch(error => {
            console.error('Error updating blocked status:', error);
            alert('حدث خطأ في تحديث الحالة');
        });
    }
    
    function renderComments(comments) {
        const container = document.getElementById('commentsList');
        
        if (comments.length === 0) {
            container.innerHTML = '<p style="color: #999; text-align: center;">لا توجد تعليقات بعد</p>';
            return;
        }
        
        container.innerHTML = comments.map(comment => `
            <div class="comment-item">
                ${canManage ? `
                    <button class="comment-delete" onclick="deleteComment(${comment.id})" title="حذف التعليق">
                        <i class="bi bi-x"></i>
                    </button>
                ` : ''}
                ${comment.author_name ? `<div class="comment-author">${comment.author_name}</div>` : ''}
                <div class="comment-body">${comment.body}</div>
                <div class="comment-time">${comment.relative_time}</div>
            </div>
        `).join('');
    }
    
    function addComment() {
        const body = document.getElementById('commentBody').value.trim();
        const authorName = document.getElementById('commentAuthor').value.trim();
        
        if (!body) {
            alert('يرجى إدخال نص التعليق');
            return;
        }
        
        fetch(`/tasks/${currentTaskId}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ body, author_name: authorName }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('commentBody').value = '';
                // Reload comments
                openTaskDetail(currentTaskId);
            } else {
                alert(data.message || 'فشل في إضافة التعليق');
            }
        })
        .catch(error => {
            console.error('Error adding comment:', error);
            alert('حدث خطأ في إضافة التعليق');
        });
    }
    
    function deleteComment(commentId) {
        if (!confirm('هل أنت متأكد من حذف هذا التعليق؟')) return;
        
        fetch(`/comments/${commentId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload comments
                openTaskDetail(currentTaskId);
            } else {
                alert(data.message || 'فشل في حذف التعليق');
            }
        })
        .catch(error => {
            console.error('Error deleting comment:', error);
            alert('حدث خطأ في حذف التعليق');
        });
    }
    
    // Close modals when clicking overlay
    document.addEventListener('click', function(e) {
        if (e.target.id === 'taskModal') {
            closeTaskModal();
        }
        if (e.target.id === 'taskDetailModal') {
            closeTaskDetail();
        }
    });
    
    // Close modals on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (document.getElementById('taskModal').classList.contains('active')) {
                closeTaskModal();
            }
            if (document.getElementById('taskDetailModal').classList.contains('active')) {
                closeTaskDetail();
            }
        }
    });
    
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
