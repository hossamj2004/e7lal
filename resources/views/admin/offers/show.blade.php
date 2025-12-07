@extends('layouts.dashboard')

@section('title', 'تفاصيل العرض - Swape It')
@section('page-title', 'تفاصيل العرض #' . $offer->id)

@section('content')
<div class="row g-4">
    <!-- User's Car -->
    <div class="col-lg-4">
        <div class="table-card h-100">
            <h5 class="fw-bold mb-4 text-secondary"><i class="bi bi-arrow-right-circle me-2"></i>سيارة المستخدم</h5>
            @if($offer->userCar->image)
                <img src="{{ asset($offer->userCar->image) }}" alt="" class="img-fluid rounded-3 mb-3">
            @endif
            <h5>{{ $offer->userCar->full_name }}</h5>
            <p class="mb-1">{{ number_format($offer->userCar->mileage) }} كم • {{ $offer->userCar->fuel_type_arabic }}</p>
            <h4 class="text-primary fw-bold">{{ $offer->userCar->formatted_fair_price }}</h4>
        </div>
    </div>
    
    <!-- Arrow -->
    <div class="col-lg-1 d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-left-right text-muted" style="font-size: 2rem;"></i>
    </div>
    
    <!-- Target Car -->
    <div class="col-lg-4">
        <div class="table-card h-100">
            <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-arrow-left-circle me-2"></i>السيارة المطلوبة</h5>
            @if($offer->car->image)
                <img src="{{ asset($offer->car->image) }}" alt="" class="img-fluid rounded-3 mb-3">
            @endif
            <h5>{{ $offer->car->full_name }}</h5>
            <p class="mb-1">{{ number_format($offer->car->mileage) }} كم • {{ $offer->car->fuel_type_arabic }}</p>
            <h4 class="text-success fw-bold">{{ $offer->car->formatted_price }}</h4>
        </div>
    </div>
    
    <!-- Offer Details -->
    <div class="col-lg-3">
        <div class="table-card h-100">
            <h5 class="fw-bold mb-4"><i class="bi bi-person me-2"></i>تفاصيل العرض</h5>
            
            <table class="table table-borderless">
                <tr>
                    <td class="text-muted">المستخدم</td>
                    <td class="fw-bold">{{ $offer->user->name }}</td>
                </tr>
                <tr>
                    <td class="text-muted">الهاتف</td>
                    <td>{{ $offer->user->phone ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">البريد</td>
                    <td>{{ $offer->user->email }}</td>
                </tr>
                <tr>
                    <td class="text-muted">الفرق المعروض</td>
                    <td class="fw-bold fs-5 {{ $offer->offered_difference > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $offer->formatted_difference }}
                    </td>
                </tr>
                <tr>
                    <td class="text-muted">الحالة</td>
                    <td>{!! $offer->status_badge !!}</td>
                </tr>
            </table>
            
            @if($offer->message)
                <div class="alert alert-light">
                    <strong>رسالة المستخدم:</strong><br>
                    {{ $offer->message }}
                </div>
            @endif
        </div>
    </div>
</div>

@if($offer->isPending())
<div class="row mt-4">
    <div class="col-lg-6">
        <div class="table-card">
            <h5 class="fw-bold mb-3 text-success"><i class="bi bi-check-circle me-2"></i>قبول العرض</h5>
            <form action="{{ route('admin.offers.accept', $offer) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">رسالة للمستخدم (اختياري)</label>
                    <textarea name="admin_response" class="form-control" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-success btn-lg w-100">
                    <i class="bi bi-check-circle me-2"></i>قبول العرض
                </button>
            </form>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="table-card">
            <h5 class="fw-bold mb-3 text-danger"><i class="bi bi-x-circle me-2"></i>رفض العرض</h5>
            <form action="{{ route('admin.offers.reject', $offer) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">سبب الرفض <span class="text-danger">*</span></label>
                    <textarea name="admin_response" class="form-control" rows="2" required></textarea>
                </div>
                <button type="submit" class="btn btn-danger btn-lg w-100">
                    <i class="bi bi-x-circle me-2"></i>رفض العرض
                </button>
            </form>
        </div>
    </div>
</div>
@endif
@endsection


