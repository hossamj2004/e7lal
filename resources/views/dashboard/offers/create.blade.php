@extends('layouts.dashboard')

@section('title', 'تقديم عرض - E7lal.com')
@section('page-title', 'تقديم عرض تبديل')

@section('content')
<div class="row g-4">
    <!-- Target Car -->
    <div class="col-lg-6">
        <div class="table-card h-100">
            <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-arrow-left-circle me-2"></i>السيارة المطلوبة</h5>
            
            <div class="text-center mb-4">
                @if($car->image)
                    <img src="{{ asset($car->image) }}" alt="{{ $car->full_name }}" class="img-fluid rounded-3" style="max-height: 200px;">
                @else
                    <div class="bg-light rounded-3 py-5">
                        <i class="bi bi-car-front-fill text-primary" style="font-size: 5rem;"></i>
                    </div>
                @endif
            </div>
            
            <h4 class="fw-bold">{{ $car->full_name }}</h4>
            
            <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="badge bg-light text-dark"><i class="bi bi-speedometer2 me-1"></i>{{ number_format($car->mileage) }} كم</span>
                <span class="badge bg-light text-dark"><i class="bi bi-fuel-pump me-1"></i>{{ $car->fuel_type_arabic }}</span>
                <span class="badge bg-light text-dark"><i class="bi bi-gear me-1"></i>{{ $car->transmission_arabic }}</span>
            </div>
            
            <h3 class="text-success fw-bold">{{ $car->formatted_price }}</h3>
        </div>
    </div>
    
    <!-- My Car -->
    <div class="col-lg-6">
        <div class="table-card h-100">
            <h5 class="fw-bold mb-4 text-secondary"><i class="bi bi-arrow-right-circle me-2"></i>سيارتي</h5>
            
            <div class="text-center mb-4">
                @if($activeCar->image)
                    <img src="{{ asset($activeCar->image) }}" alt="{{ $activeCar->full_name }}" class="img-fluid rounded-3" style="max-height: 200px;">
                @else
                    <div class="bg-light rounded-3 py-5">
                        <i class="bi bi-car-front-fill text-secondary" style="font-size: 5rem;"></i>
                    </div>
                @endif
            </div>
            
            <h4 class="fw-bold">{{ $activeCar->full_name }}</h4>
            
            <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="badge bg-light text-dark"><i class="bi bi-speedometer2 me-1"></i>{{ number_format($activeCar->mileage) }} كم</span>
                <span class="badge bg-light text-dark"><i class="bi bi-fuel-pump me-1"></i>{{ $activeCar->fuel_type_arabic }}</span>
                <span class="badge bg-light text-dark"><i class="bi bi-gear me-1"></i>{{ $activeCar->transmission_arabic }}</span>
            </div>
            
            <h3 class="text-primary fw-bold">{{ $activeCar->formatted_fair_price }}</h3>
        </div>
    </div>
</div>

<!-- Offer Form -->
<div class="table-card mt-4">
    <h5 class="fw-bold mb-4"><i class="bi bi-calculator me-2"></i>تفاصيل العرض</h5>
    
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        <strong>الفرق المتوقع:</strong>
        @if($suggestedDifference !== null)
            @if($suggestedDifference > 0)
                <span class="text-danger fw-bold">+ {{ number_format($suggestedDifference) }} جنيه</span> (مطلوب منك)
            @elseif($suggestedDifference < 0)
                <span class="text-success fw-bold">- {{ number_format(abs($suggestedDifference)) }} جنيه</span> (لصالحك)
            @else
                <span class="text-success fw-bold">بدون فرق</span>
            @endif
        @else
            غير متاح
        @endif
    </div>
    
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p class="mb-0">{{ $error }}</p>
            @endforeach
        </div>
    @endif
    
    <form action="{{ route('offers.store', $car) }}" method="POST">
        @csrf
        
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">الفرق اللي تعرضه (جنيه) <span class="text-danger">*</span></label>
                <input type="number" name="offered_difference" class="form-control form-control-lg" 
                       value="{{ old('offered_difference', $suggestedDifference) }}" required>
                <small class="text-muted">رقم موجب = هتدفع فرق، رقم سالب = هتاخد فرق</small>
            </div>
            
            <div class="col-12">
                <label class="form-label fw-semibold">رسالة للإدارة (اختياري)</label>
                <textarea name="message" class="form-control" rows="3" placeholder="أي ملاحظات أو تفاصيل إضافية...">{{ old('message') }}</textarea>
            </div>
        </div>
        
        <div class="border-top mt-4 pt-4">
            <button type="submit" class="btn btn-primary-custom btn-lg">
                <i class="bi bi-send me-2"></i>إرسال العرض
            </button>
            <a href="{{ route('cars') }}" class="btn btn-outline-secondary btn-lg me-2">إلغاء</a>
        </div>
    </form>
</div>
@endsection
