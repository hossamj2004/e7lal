@extends('layouts.app')

@section('title', 'السيارات المتاحة - E7lal.com')

@push('styles')
<style>
    /* Car card hover effects */
    .hover-overlay {
        transition: opacity 0.3s ease;
    }
    .card:hover .hover-overlay {
        opacity: 1 !important;
    }
    .card:hover img {
        transform: scale(1.05);
    }
    .inset-0 {
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<section class="page-header" style="padding-top: 120px; padding-bottom: 40px;">
    <div class="container">
        <h1 class="fw-bold" style="color: white;">السيارات المتاحة للتبديل</h1>
        <p class="text-muted" style="color: white !important;">اختار عربيتك الجديدة وقدم عرض التبديل</p>
    </div>
</section>

<!-- Cars Section -->
<section class="py-5">
    <div class="container">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Active Car Info -->
        @if($activeCar)
            <div class="alert alert-info d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
                <div>
                    <strong><i class="bi bi-car-front me-2"></i>عربيتك النشطة:</strong>
                    {{ $activeCar->full_name }} - السعر العادل: {{ $activeCar->formatted_fair_price }}
                </div>
                <a href="{{ route('my-cars.index') }}" class="btn btn-outline-primary btn-sm">تغيير العربية النشطة</a>
            </div>
        @elseif(auth()->check())
            <div class="alert alert-warning mb-4">
                <i class="bi bi-exclamation-triangle me-2"></i>
                لازم تضيف عربيتك وتفعّلها الأول عشان تقدر تقدم عرض تبديل
                <a href="{{ route('my-cars.create') }}" class="btn btn-warning btn-sm ms-2">أضف عربيتك</a>
            </div>
        @endif

        <!-- Cars Grid -->
        <div class="row g-4">

            @forelse($cars as $car)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
                        @if($car->hasImages())
                            <a href="{{ route('cars.show', $car) }}" class="text-decoration-none">
                                <div style="position: relative; cursor: pointer;">
                                    <img src="{{ $car->getFirstImage() }}"
                                         class="card-img-top"
                                         alt="{{ $car->full_name }}"
                                         style="height: 250px; object-fit: scale-down; background-color: #f8f9fa; transition: transform 0.2s;">
                                    <div class="position-absolute inset-0 bg-dark bg-opacity-25 d-flex align-items-center justify-content-center opacity-0 hover-overlay">
                                        <i class="bi bi-eye-fill text-white fs-3"></i>
                                    </div>
                                </div>
                            </a>
                        @else
                            <a href="{{ route('cars.show', $car) }}" class="text-decoration-none">
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                     style="height: 200px; cursor: pointer;">
                                    <i class="bi bi-car-front-fill text-muted" style="font-size: 5rem;"></i>
                                </div>
                            </a>
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0">{{ $car->full_name }}</h5>
                                {!! $car->status_badge !!}
                            </div>

                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-light text-dark"><i class="bi bi-speedometer2 me-1"></i>{{ number_format($car->mileage) }} كم</span>
                                <span class="badge bg-light text-dark"><i class="bi bi-fuel-pump me-1"></i>{{ $car->fuel_type_arabic }}</span>
                                <span class="badge bg-light text-dark"><i class="bi bi-gear me-1"></i>{{ $car->transmission_arabic }}</span>
                            </div>

                            <h4 class="text-success fw-bold mb-3">{{ $car->formatted_price }}</h4>

                            @if($activeCar && $activeCar->fair_price)
                                @php $diff = $activeCar->calculateDifference($car); @endphp
                                <div class="alert alert-light py-2 mb-3">
                                    <small class="text-muted">الفرق:</small>
                                    <strong class="{{ $diff > 0 ? 'text-danger' : 'text-success' }}">
                                        @if($diff > 0)
                                            + {{ number_format($diff) }} جنيه
                                        @elseif($diff < 0)
                                            - {{ number_format(abs($diff)) }} جنيه
                                        @else
                                            بدون فرق
                                        @endif
                                    </strong>
                                </div>
                            @endif

                            <div class="d-flex gap-2">
                                <a href="{{ route('cars.show', $car) }}" class="btn btn-outline-primary flex-grow-1">
                                    <i class="bi bi-eye me-1"></i>التفاصيل
                                </a>
                                @if(auth()->check() && $activeCar && $activeCar->isPriced() && $car->isAvailable())
                                    <a href="{{ route('offers.create', $car) }}" class="btn btn-primary-custom flex-grow-1">
                                        <i class="bi bi-arrow-left-right me-1"></i>بدّل
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bi bi-car-front-fill text-muted mb-3" style="font-size: 5rem;"></i>
                        <h4>لا توجد سيارات متاحة حالياً</h4>
                        <p class="text-muted">جرب تغيير معايير البحث أو تابعنا قريباً</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $cars->withQueryString()->links() }}
        </div>
    </div>
</section>
@endsection
