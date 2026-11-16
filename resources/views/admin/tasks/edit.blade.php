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
                    <select name="board_id" class="form-select @error('board_id') is-invalid @enderror" required>
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

                <div class="mb-4">
                    <label class="form-label fw-bold">الحالة <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="todo" {{ old('status', $task->status) == 'todo' ? 'selected' : '' }}>لازم يتعمل (To Do)</option>
                        <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>شغال عليها (In Progress)</option>
                        <option value="done" {{ old('status', $task->status) == 'done' ? 'selected' : '' }}>خلصت (Done)</option>
                    </select>
                    @error('status')
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
