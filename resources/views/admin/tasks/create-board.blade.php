@extends('layouts.dashboard')

@section('title', 'إضافة لوحة جديدة - E7lal.com')
@section('page-title', 'إضافة لوحة جديدة')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="table-card">
            <form action="{{ route('admin.tasks.store-board') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-bold">اسم اللوحة <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" required placeholder="مثال: مشروع التطوير الجديد">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">اسم مميز للوحة لتسهيل التعرف عليها</small>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="bi bi-check-circle me-2"></i>
                        حفظ اللوحة
                    </button>
                    <a href="{{ route('admin.tasks.boards') }}" class="btn btn-outline-secondary">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="table-card">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-info-circle text-info me-2"></i>
                ملاحظات
            </h6>
            <ul class="text-muted" style="font-size: 0.9rem;">
                <li class="mb-2">كل لوحة تحصل على رابط مشاركة فريد</li>
                <li class="mb-2">يمكن مشاركة اللوحة مع أي شخص بدون تسجيل دخول</li>
                <li class="mb-2">اللوحة الافتراضية هي التي تظهر في صفحة <code>/tasks</code></li>
                <li class="mb-2">يمكن حذف اللوحات العادية فقط (ليس الافتراضية)</li>
                <li class="mb-2">عند حذف لوحة، يتم حذف جميع المهام المرتبطة بها</li>
            </ul>
        </div>
    </div>
</div>
@endsection
