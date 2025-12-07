@extends('layouts.dashboard')

@section('title', 'لوحة التحكم - Swape It')
@section('page-title', 'لوحة التحكم')

@section('content')
<div class="row g-4 mb-4">
    <!-- Active Car Card -->
    <div class="col-lg-6">
        <div class="stat-card h-100">
            <h5 class="fw-bold mb-4"><i class="bi bi-star-fill text-warning me-2"></i>العربية النشطة</h5>
            @if($activeCar)
                <div class="d-flex align-items-center gap-4">
                    <div class="icon" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
                        <i class="bi bi-car-front-fill"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $activeCar->full_name }}</h4>
                        <p class="text-muted mb-2">
                            {{ $activeCar->mileage }} كم • {{ $activeCar->fuel_type_arabic }} • {{ $activeCar->transmission_arabic }}
                        </p>
                        @if($activeCar->isPriced())
                            <span class="badge bg-success fs-6">السعر العادل: {{ $activeCar->formatted_fair_price }}</span>
                        @else
                            <span class="badge bg-warning">قيد التقييم</span>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-car-front-fill text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-3">مفيش عربية نشطة</p>
                    <a href="{{ route('my-cars.create') }}" class="btn btn-primary-custom">
                        <i class="bi bi-plus-circle me-2"></i>أضف عربيتك
                    </a>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Stats -->
    <div class="col-lg-6">
        <div class="row g-4">
            <div class="col-6">
                <div class="stat-card text-center">
                    <div class="icon mx-auto mb-3" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                        <i class="bi bi-car-front"></i>
                    </div>
                    <h3 class="fw-bold">{{ $userCars->count() }}</h3>
                    <p class="text-muted mb-0">سياراتي</p>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card text-center">
                    <div class="icon mx-auto mb-3" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                        <i class="bi bi-send"></i>
                    </div>
                    <h3 class="fw-bold">{{ $offers->count() }}</h3>
                    <p class="text-muted mb-0">عروضي</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- My Cars -->
    <div class="col-lg-6">
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">سياراتي</h5>
                <a href="{{ route('my-cars.index') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
            </div>
            
            @forelse($userCars->take(3) as $car)
                <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light rounded-3 p-2">
                            <i class="bi bi-car-front-fill text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $car->full_name }}</h6>
                            <small class="text-muted">{{ $car->formatted_expected_price }}</small>
                        </div>
                    </div>
                    <div>
                        {!! $car->status_badge !!}
                        @if($car->is_active)
                            <span class="badge bg-info ms-1">نشطة</span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-muted text-center py-4">لم تضف أي سيارات بعد</p>
            @endforelse
        </div>
    </div>
    
    <!-- Recent Offers -->
    <div class="col-lg-6">
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">عروضي الأخيرة</h5>
                <a href="{{ route('offers.index') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
            </div>
            
            @forelse($offers as $offer)
                <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light rounded-3 p-2">
                            <i class="bi bi-arrow-left-right text-success"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $offer->car->full_name }}</h6>
                            <small class="text-muted">الفرق: {{ $offer->formatted_difference }}</small>
                        </div>
                    </div>
                    {!! $offer->status_badge !!}
                </div>
            @empty
                <p class="text-muted text-center py-4">لم تقدم أي عروض بعد</p>
            @endforelse
        </div>
    </div>
</div>

@if($activeCar && $activeCar->isPriced())
<div class="mt-4">
    <a href="{{ route('cars') }}" class="btn btn-primary-custom btn-lg">
        <i class="bi bi-search me-2"></i>تصفح السيارات المتاحة للتبديل
    </a>
</div>
@endif
@endsection


