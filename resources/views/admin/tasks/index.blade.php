@extends('layouts.dashboard')

@section('title', 'إدارة المهام - E7lal.com')
@section('page-title', 'إدارة المهام')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0">إدارة المهام واللوحات</h4>
                <p class="text-muted mb-0">إدارة مهام ولوحات Kanban</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.tasks.boards') }}" class="btn btn-outline-primary">
                    <i class="bi bi-grid-3x3"></i> إدارة اللوحات
                </a>
                <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary-custom">
                    <i class="bi bi-plus-circle"></i> إضافة مهمة جديدة
                </a>
            </div>
        </div>
    </div>
</div>

@if($defaultBoard)
<div class="row mb-3">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            <strong>اللوحة الافتراضية:</strong> {{ $defaultBoard->name }}
            <a href="{{ route('tasks') }}" target="_blank" class="btn btn-sm btn-outline-primary ms-3">
                <i class="bi bi-eye"></i> عرض اللوحة العامة
            </a>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اللوحة</th>
                            <th>العنوان</th>
                            <th>الوصف</th>
                            <th>الحالة</th>
                            <th>الترتيب</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($boards as $board)
                            @foreach($board->tasks as $task)
                                <tr>
                                    <td>{{ $task->id }}</td>
                                    <td>
                                        {{ $board->name }}
                                        @if($board->is_default)
                                            <span class="badge bg-primary">افتراضية</span>
                                        @endif
                                    </td>
                                    <td>{{ $task->title }}</td>
                                    <td>{{ Str::limit($task->description, 50) }}</td>
                                    <td>
                                        @if($task->status === 'todo')
                                            <span class="badge bg-warning">لازم يتعمل</span>
                                        @elseif($task->status === 'in_progress')
                                            <span class="badge bg-info">شغال عليها</span>
                                        @else
                                            <span class="badge bg-success">خلصت</span>
                                        @endif
                                    </td>
                                    <td>{{ $task->position }}</td>
                                    <td>{{ $task->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه المهمة؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    لا توجد مهام حالياً
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
