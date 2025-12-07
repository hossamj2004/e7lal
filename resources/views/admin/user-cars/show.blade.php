@extends('layouts.dashboard')

@section('title', 'تقييم سيارة - Swape It')
@section('page-title', 'تقييم سيارة: ' . $userCar->full_name)

@section('content')
<div class="row g-4">
    <div class="col-lg-6">
        <div class="table-card h-100">
            <h5 class="fw-bold mb-4"><i class="bi bi-car-front me-2"></i>بيانات السيارة</h5>
            
            <div class="text-center mb-4">
                @if($userCar->image)
                    <img src="{{ asset($userCar->image) }}" alt="" class="img-fluid rounded-3" style="max-height: 250px;">
                @else
                    <div class="bg-light rounded-3 py-5">
                        <i class="bi bi-car-front-fill text-muted" style="font-size: 5rem;"></i>
                    </div>
                @endif
            </div>
            
            <table class="table table-borderless">
                <tr>
                    <td class="text-muted">السيارة</td>
                    <td class="fw-bold">{{ $userCar->full_name }}</td>
                </tr>
                <tr>
                    <td class="text-muted">اللون</td>
                    <td>{{ $userCar->color ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">الكيلومترات</td>
                    <td>{{ number_format($userCar->mileage) }} كم</td>
                </tr>
                <tr>
                    <td class="text-muted">نوع الوقود</td>
                    <td>{{ $userCar->fuel_type_arabic }}</td>
                </tr>
                <tr>
                    <td class="text-muted">ناقل الحركة</td>
                    <td>{{ $userCar->transmission_arabic }}</td>
                </tr>
                <tr>
                    <td class="text-muted">السعر المتوقع</td>
                    <td class="fw-bold text-primary fs-5">{{ $userCar->formatted_expected_price }}</td>
                </tr>
                @if($userCar->description)
                <tr>
                    <td class="text-muted">الوصف</td>
                    <td>{{ $userCar->description }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="table-card h-100">
            <h5 class="fw-bold mb-4"><i class="bi bi-person me-2"></i>بيانات المالك</h5>
            
            <table class="table table-borderless mb-4">
                <tr>
                    <td class="text-muted">الاسم</td>
                    <td class="fw-bold">{{ $userCar->user->name }}</td>
                </tr>
                <tr>
                    <td class="text-muted">البريد</td>
                    <td>{{ $userCar->user->email }}</td>
                </tr>
                <tr>
                    <td class="text-muted">الهاتف</td>
                    <td>{{ $userCar->user->phone ?? '-' }}</td>
                </tr>
            </table>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <p class="mb-0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            
            @if($userCar->isPending())
                <div class="border-top pt-4">
                    <h6 class="fw-bold mb-3">تسعير السيارة</h6>
                    <form action="{{ route('admin.user-cars.price', $userCar) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">السعر العادل (جنيه) <span class="text-danger">*</span></label>
                            <input type="number" name="fair_price" class="form-control form-control-lg" 
                                   value="{{ old('fair_price', $userCar->user_expected_price) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ملاحظات للمستخدم</label>
                            <textarea name="admin_notes" class="form-control" rows="2">{{ old('admin_notes') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="bi bi-check-circle me-2"></i>قبول وتسعير
                        </button>
                    </form>
                    
                    <h6 class="fw-bold mb-3">رفض السيارة</h6>
                    <form action="{{ route('admin.user-cars.reject', $userCar) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">سبب الرفض <span class="text-danger">*</span></label>
                            <textarea name="admin_notes" class="form-control" rows="2" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('هل أنت متأكد من رفض هذه السيارة؟')">
                            <i class="bi bi-x-circle me-2"></i>رفض
                        </button>
                    </form>
                </div>
            @else
                <div class="alert alert-info">
                    الحالة: {!! $userCar->status_badge !!}
                    @if($userCar->fair_price)
                        <br>السعر العادل: <strong>{{ $userCar->formatted_fair_price }}</strong>
                    @endif
                    @if($userCar->admin_notes)
                        <br>الملاحظات: {{ $userCar->admin_notes }}
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection


