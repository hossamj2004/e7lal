@extends('layouts.dashboard')

@section('title', 'لوحة تحكم المدير - Swape It')
@section('page-title', 'لوحة تحكم المدير')

@section('content')
<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <i class="bi bi-car-front"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['total_cars'] }}</h3>
                    <small class="text-muted">إجمالي السيارات</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="icon" style="background: linear-gradient(135deg, #11998e, #38ef7d);">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['available_cars'] }}</h3>
                    <small class="text-muted">سيارات متاحة</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['pending_user_cars'] }}</h3>
                    <small class="text-muted">تحتاج تسعير</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="icon" style="background: linear-gradient(135deg, #fa709a, #fee140);">
                    <i class="bi bi-bell"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['pending_offers'] }}</h3>
                    <small class="text-muted">عروض جديدة</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Pending User Cars -->
    <div class="col-lg-6">
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-clock-history text-warning me-2"></i>سيارات تحتاج تسعير</h5>
                <a href="{{ route('admin.user-cars.pending') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
            </div>
            
            @forelse($pendingUserCars as $car)
                <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                    <div>
                        <h6 class="mb-0">{{ $car->full_name }}</h6>
                        <small class="text-muted">{{ $car->user->name }} - {{ $car->formatted_expected_price }}</small>
                    </div>
                    <a href="{{ route('admin.user-cars.show', $car) }}" class="btn btn-sm btn-primary-custom">
                        <i class="bi bi-currency-dollar"></i> تسعير
                    </a>
                </div>
            @empty
                <p class="text-muted text-center py-4">لا توجد سيارات معلقة</p>
            @endforelse
        </div>
    </div>
    
    <!-- Pending Offers -->
    <div class="col-lg-6">
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-bell text-danger me-2"></i>عروض جديدة</h5>
                <a href="{{ route('admin.offers.pending') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
            </div>
            
            @forelse($pendingOffers as $offer)
                <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                    <div>
                        <h6 class="mb-0">{{ $offer->user->name }}</h6>
                        <small class="text-muted">
                            {{ $offer->userCar->full_name }} ⟵ {{ $offer->car->full_name }}
                        </small>
                    </div>
                    <a href="{{ route('admin.offers.show', $offer) }}" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-eye"></i> عرض
                    </a>
                </div>
            @empty
                <p class="text-muted text-center py-4">لا توجد عروض معلقة</p>
            @endforelse
        </div>
    </div>
</div>
@endsection


