@extends('layouts.dashboard')

@section('title', 'تعديل مستخدم - E7lal.com')
@section('page-title', 'تعديل: ' . $user->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="table-card">
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <p class="mb-0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">الاسم <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">البريد الإلكتروني <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">رقم الهاتف</label>
                    <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">كلمة المرور</label>
                    <input type="password" name="password" class="form-control">
                    <small class="text-muted">اتركها فارغة إذا لم ترد تغييرها</small>
                </div>
                
                <div class="mb-4">
                    <div class="form-check">
                        <input type="checkbox" name="is_admin" value="1" class="form-check-input" id="is_admin" {{ $user->is_admin ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_admin">مدير نظام</label>
                    </div>
                </div>
                
                <div class="border-top pt-4">
                    <button type="submit" class="btn btn-primary-custom btn-lg">
                        <i class="bi bi-check-circle me-2"></i>حفظ التعديلات
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-lg me-2">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
