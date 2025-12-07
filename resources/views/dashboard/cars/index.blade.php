@extends('layouts.dashboard')

@section('title', 'سياراتي - Swape It')
@section('page-title', 'سياراتي')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">إدارة سياراتك المسجلة للتبديل</p>
    <a href="{{ route('my-cars.create') }}" class="btn btn-primary-custom">
        <i class="bi bi-plus-circle me-2"></i>أضف سيارة جديدة
    </a>
</div>

<div class="row g-4">
    @forelse($userCars as $car)
        <div class="col-lg-4 col-md-6">
            <div class="table-card h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        {!! $car->status_badge !!}
                        @if($car->is_active)
                            <span class="badge bg-info">نشطة</span>
                        @endif
                    </div>
                    @if($car->isPriced() && !$car->is_active)
                        <form action="{{ route('my-cars.set-active', $car) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-check-circle"></i> تفعيل
                            </button>
                        </form>
                    @endif
                </div>
                
                <div class="text-center mb-3">
                    @if($car->image)
                        <img src="{{ asset($car->image) }}" alt="{{ $car->full_name }}" class="img-fluid rounded-3" style="max-height: 150px;">
                    @else
                        <div class="bg-light rounded-3 py-5">
                            <i class="bi bi-car-front-fill text-muted" style="font-size: 4rem;"></i>
                        </div>
                    @endif
                </div>
                
                <h5 class="fw-bold">{{ $car->full_name }}</h5>
                
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-light text-dark"><i class="bi bi-speedometer2 me-1"></i>{{ number_format($car->mileage) }} كم</span>
                    <span class="badge bg-light text-dark"><i class="bi bi-fuel-pump me-1"></i>{{ $car->fuel_type_arabic }}</span>
                    <span class="badge bg-light text-dark"><i class="bi bi-gear me-1"></i>{{ $car->transmission_arabic }}</span>
                </div>
                
                <div class="border-top pt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">سعرك المتوقع:</span>
                        <span class="fw-bold">{{ $car->formatted_expected_price }}</span>
                    </div>
                    @if($car->fair_price)
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">السعر العادل:</span>
                            <span class="fw-bold text-success">{{ $car->formatted_fair_price }}</span>
                        </div>
                    @endif
                </div>
                
                @if($car->admin_notes)
                    <div class="alert alert-info mt-3 mb-0 small">
                        <i class="bi bi-info-circle me-1"></i>{{ $car->admin_notes }}
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="table-card text-center py-5">
                <i class="bi bi-car-front-fill text-muted mb-3" style="font-size: 5rem;"></i>
                <h4>مفيش سيارات مسجلة</h4>
                <p class="text-muted">أضف سيارتك الأولى لبدء عملية التبديل</p>
                <a href="{{ route('my-cars.create') }}" class="btn btn-primary-custom">
                    <i class="bi bi-plus-circle me-2"></i>أضف سيارة جديدة
                </a>
            </div>
        </div>
    @endforelse
</div>
@endsection


