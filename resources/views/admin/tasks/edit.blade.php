@extends('layouts.dashboard')

@section('title', 'تعديل المهمة - E7lal.com')
@section('page-title', 'تعديل المهمة')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="table-card">
            <form action="{{ route('admin.tasks.update', $task) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="form-label fw-bold">اللوحة <span class="text-danger">*</span></label>
                    <select name="board_id" id="board_id" class="form-select @error('board_id') is-invalid @enderror" required onchange="updateColumns()">
                        <option value="">اختر اللوحة</option>
                        @foreach($boards as $board)
                            <option value="{{ $board->id }}" {{ old('board_id', $task->board_id) == $board->id ? 'selected' : '' }}>
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
                           value="{{ old('title', $task->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">الوصف (اختياري)</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                              rows="4">{{ old('description', $task->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="bi bi-check-circle me-2"></i>
                        تحديث المهمة
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
                معلومات المهمة
            </h6>
            <ul class="list-unstyled">
                <li class="mb-2">
                    <strong>رقم المهمة:</strong> #{{ $task->id }}
                </li>
                <li class="mb-2">
                    <strong>العمود الحالي:</strong> {{ $task->column->name }}
                </li>
                <li class="mb-2">
                    <strong>الترتيب الحالي:</strong> {{ $task->position }}
                </li>
                <li class="mb-2">
                    <strong>تاريخ الإنشاء:</strong> {{ $task->created_at->format('Y-m-d H:i') }}
                </li>
                <li class="mb-2">
                    <strong>آخر تحديث:</strong> {{ $task->updated_at->format('Y-m-d H:i') }}
                </li>
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
    
    const currentColumnId = {{ old('column_id', $task->column_id) }};
    
    function updateColumns() {
        const boardId = document.getElementById('board_id').value;
        const columnSelect = document.getElementById('column_id');
        
        columnSelect.innerHTML = '<option value="">اختر العمود</option>';
        
        if (boardId && boardsData[boardId]) {
            boardsData[boardId].columns.forEach(column => {
                const option = document.createElement('option');
                option.value = column.id;
                option.textContent = column.name;
                if (column.id === currentColumnId) {
                    option.selected = true;
                }
                columnSelect.appendChild(option);
            });
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        updateColumns();
    });
</script>
@endsection
