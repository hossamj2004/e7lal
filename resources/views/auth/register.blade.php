@extends('layouts.app')

@section('title', 'إنشاء حساب - E7lal.com')

@section('content')
<section class="page-header" style="min-height: 100vh; display: flex; align-items: center; padding: 120px 0 60px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg border-0" style="border-radius: 20px;">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <img src="/imgs/logo.png" alt="E7lal.com" height="60" class="mb-3">
                            <h3 class="fw-bold" style="color: var(--dark-color);">إنشاء حساب جديد</h3>
                            <p class="text-muted">انضم لينا وبدّل عربيتك</p>
                        </div>
                        
                        @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <p class="mb-0">{{ $error }}</p>
                            @endforeach
                        </div>
                        @endif
                        
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">الاسم</label>
                                <input type="text" name="name" class="form-control form-control-lg" 
                                       value="{{ old('name') }}" required style="border-radius: 12px;">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">البريد الإلكتروني</label>
                                <input type="email" name="email" class="form-control form-control-lg" 
                                       value="{{ old('email') }}" required style="border-radius: 12px;">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">رقم الموبايل</label>
                                <input type="tel" name="phone" class="form-control form-control-lg" 
                                       value="{{ old('phone') }}" style="border-radius: 12px;">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">كلمة المرور</label>
                                <input type="password" name="password" class="form-control form-control-lg" 
                                       required style="border-radius: 12px;">
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold">تأكيد كلمة المرور</label>
                                <input type="password" name="password_confirmation" class="form-control form-control-lg" 
                                       required style="border-radius: 12px;">
                            </div>
                            
                            <button type="submit" class="btn btn-primary-custom w-100 py-3 mb-4">
                                إنشاء الحساب
                            </button>
                            
                            <p class="text-center mb-0">
                                عندك حساب؟ 
                                <a href="{{ route('login') }}" style="color: var(--secondary-color); font-weight: 600;">سجّل دخول</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
