@extends('layouts.dashboard')

@section('title', 'إدارة اللوحات - E7lal.com')
@section('page-title', 'إدارة اللوحات')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0">إدارة اللوحات</h4>
                <p class="text-muted mb-0">إدارة لوحات Kanban المختلفة</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-right"></i> رجوع للمهام
                </a>
                <a href="{{ route('admin.tasks.create-board') }}" class="btn btn-primary-custom">
                    <i class="bi bi-plus-circle"></i> إضافة لوحة جديدة
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    @forelse($boards as $board)
        <div class="col-lg-6">
            <div class="table-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">
                            {{ $board->name }}
                            @if($board->is_default)
                                <span class="badge bg-primary">افتراضية</span>
                            @endif
                        </h5>
                        <small class="text-muted">
                            <i class="bi bi-folder me-1"></i>
                            {{ $board->tasks_count }} مهمة
                        </small>
                    </div>
                    @if(!$board->is_default)
                        <form action="{{ route('admin.tasks.destroy-board', $board) }}" method="POST" 
                              onsubmit="return confirm('هل أنت متأكد من حذف هذه اللوحة؟ سيتم حذف جميع المهام المرتبطة بها.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">رابط المشاركة:</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" 
                               value="{{ $board->share_url }}" 
                               id="share-{{ $board->id }}" 
                               readonly 
                               style="direction: ltr; text-align: left;">
                        <button class="btn btn-sm btn-outline-secondary" 
                                onclick="copyBoardLink({{ $board->id }})">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('tasks.show', $board->share_token) }}" 
                       target="_blank" 
                       class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye me-1"></i>
                        عرض اللوحة
                    </a>
                    <a href="{{ route('admin.tasks.index') }}" 
                       class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-list-task me-1"></i>
                        عرض المهام
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="table-card text-center py-5">
                <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                <p class="text-muted">لا توجد لوحات حالياً</p>
                <a href="{{ route('admin.tasks.create-board') }}" class="btn btn-primary-custom">
                    <i class="bi bi-plus-circle me-2"></i>
                    إضافة لوحة جديدة
                </a>
            </div>
        </div>
    @endforelse
</div>
@endsection

@section('scripts')
<script>
    function copyBoardLink(boardId) {
        const input = document.getElementById('share-' + boardId);
        input.select();
        document.execCommand('copy');
        
        const btn = event.target.closest('button');
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check2"></i>';
        
        setTimeout(() => {
            btn.innerHTML = originalHtml;
        }, 2000);
    }
</script>
@endsection
