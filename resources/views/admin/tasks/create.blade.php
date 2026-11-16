@extends('layouts.dashboard')

@section('title', 'إضافة مهمة جديدة - E7lal.com')
@section('page-title', 'إضافة مهمة جديدة')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="table-card">
            <form action="{{ route('admin.tasks.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-bold">اللوحة <span class="text-danger">*</span></label>
                    <select name="board_id" id="board_id" class="form-select @error('board_id') is-invalid @enderror" required onchange="updateColumns()">
                        <option value="">اختر اللوحة</option>
                        @foreach($boards as $board)
                            <option value="{{ $board->id }}" {{ old('board_id') == $board->id ? 'selected' : '' }}>
                                {{ $board->name }}
                                @if($board->is_default) (افتراضية) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('board_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">العمود <span class="text-danger">*</span></label>
                    <select name="column_id" id="column_id" class="form-select @error('column_id') is-invalid @enderror" required>
                        <option value="">اختر العمود</option>
                    </select>
                    @error('column_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">عنوان المهمة <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                           value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">الوصف (اختياري)</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                              rows="4">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="bi bi-check-circle me-2"></i>
                        حفظ المهمة
                    </button>
                    <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="table-card">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-info-circle text-info me-2"></i>
                ملاحظات
            </h6>
            <ul class="text-muted" style="font-size: 0.9rem;">
                <li class="mb-2">عنوان المهمة مطلوب</li>
                <li class="mb-2">الوصف اختياري ولكنه مفيد لتوضيح تفاصيل المهمة</li>
                <li class="mb-2">يمكن للزوار نقل المهام بين الأعمدة في الصفحة العامة</li>
                <li class="mb-2">لا يمكن تعديل أو حذف المهام إلا من لوحة التحكم</li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const boardsData = @json($boards->map(fn($board) => [
        'id' => $board->id,
        'columns' => $board->columns->map(fn($col) => ['id' => $col->id, 'name' => $col->name])
    ])->keyBy('id'));
    
    function updateColumns() {
        const boardId = document.getElementById('board_id').value;
        const columnSelect = document.getElementById('column_id');
        
        columnSelect.innerHTML = '<option value="">اختر العمود</option>';
        
        if (boardId && boardsData[boardId]) {
            boardsData[boardId].columns.forEach(column => {
                const option = document.createElement('option');
                option.value = column.id;
                option.textContent = column.name;
                columnSelect.appendChild(option);
            });
        }
    }
    
    // Initialize columns if board is already selected
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('board_id').value) {
            updateColumns();
        }
    });
</script>
@endsection
