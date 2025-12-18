@extends('layouts.app')

@section('title', $car->full_name . ' - E7lal.com')

@section('content')
<section class="page-header" style="padding-top: 120px; padding-bottom: 40px;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cars') }}">السيارات</a></li>
                <li class="breadcrumb-item active">{{ $car->full_name }}</li>
            </ol>
        </nav>
    </div>
</section>

<section class="pb-5">
    <div class="container">
        <div class="row g-4">
            <!-- Car Images -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
                    @if($car->hasImages())
                        @if($car->getImageCount() > 1)
                            <!-- Image Carousel for multiple images -->
                            <div id="carImagesCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach($car->images as $index => $image)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <img src="{{ $image }}" class="d-block w-100" alt="{{ $car->full_name }}" style="height: 400px; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carImagesCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carImagesCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                            <!-- Image thumbnails -->
                            <div class="d-flex justify-content-center mt-3 flex-wrap">
                                @foreach($car->images as $index => $image)
                                    <img src="{{ $image }}" class="me-2 mb-2 rounded shadow-sm" style="width: 80px; height: 60px; object-fit: cover; cursor: pointer; border: 2px solid {{ $index === 0 ? '#1a5f7a' : 'transparent' }};" data-bs-target="#carImagesCarousel" data-bs-slide-to="{{ $index }}">
                                @endforeach
                            </div>
                        @else
                            <!-- Single image -->
                            <img src="{{ $car->getFirstImage() }}" class="img-fluid" alt="{{ $car->full_name }}" style="width: 100%; height: 400px; object-fit: cover;">
                        @endif
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                            <i class="bi bi-car-front-fill text-muted" style="font-size: 8rem;"></i>
                        </div>
                    @endif
                </div>

                <!-- YouTube Video -->
                @if($car->hasYouTubeVideo())
                    <div class="mt-3">
                        <h5 class="fw-bold mb-3" style="color: var(--dark-color);">
                            <i class="bi bi-youtube text-danger me-2"></i>فيديو السيارة
                        </h5>
                        <div class="ratio ratio-16x9" style="border-radius: 20px; overflow: hidden;">
                            <iframe src="{{ $car->getYouTubeEmbedUrl() }}" title="YouTube video player"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen
                                    style="border-radius: 20px;">
                            </iframe>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Car Details -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h2 class="fw-bold" style="color: var(--dark-color);">{{ $car->full_name }}</h2>
                            {!! $car->status_badge !!}
                        </div>
                        
                        <h3 class="text-success fw-bold mb-4">{{ $car->formatted_price }}</h3>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="bg-light rounded-3 p-3 text-center">
                                    <i class="bi bi-speedometer2 text-primary mb-2" style="font-size: 1.5rem;"></i>
                                    <div class="fw-semibold">{{ number_format($car->mileage) }} كم</div>
                                    <small class="text-muted">الكيلومترات</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded-3 p-3 text-center">
                                    <i class="bi bi-calendar text-primary mb-2" style="font-size: 1.5rem;"></i>
                                    <div class="fw-semibold">{{ $car->year }}</div>
                                    <small class="text-muted">سنة الصنع</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded-3 p-3 text-center">
                                    <i class="bi bi-fuel-pump text-primary mb-2" style="font-size: 1.5rem;"></i>
                                    <div class="fw-semibold">{{ $car->fuel_type_arabic }}</div>
                                    <small class="text-muted">نوع الوقود</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded-3 p-3 text-center">
                                    <i class="bi bi-gear text-primary mb-2" style="font-size: 1.5rem;"></i>
                                    <div class="fw-semibold">{{ $car->transmission_arabic }}</div>
                                    <small class="text-muted">ناقل الحركة</small>
                                </div>
                            </div>
                        </div>
                        
                        @if($car->color)
                            <p><strong>اللون:</strong> {{ $car->color }}</p>
                        @endif
                        
                        @if($car->description)
                            <div class="mb-4">
                                <strong>الوصف:</strong>
                                <p class="text-muted mt-2">{{ $car->description }}</p>
                            </div>
                        @endif
                        
                        @if($difference !== null)
                            <div class="alert {{ $difference > 0 ? 'alert-warning' : 'alert-success' }} mb-4">
                                <strong>الفرق مع عربيتك:</strong>
                                <span class="fs-5 fw-bold">
                                    @if($difference > 0)
                                        + {{ number_format($difference) }} جنيه (هتدفع فرق)
                                    @elseif($difference < 0)
                                        - {{ number_format(abs($difference)) }} جنيه (لصالحك)
                                    @else
                                        بدون فرق!
                                    @endif
                                </span>
                            </div>
                        @endif
                        
                        <div class="d-flex gap-3">
                            @if(auth()->check())
                                @if($activeCar && $activeCar->isPriced() && $car->isAvailable())
                                    <a href="{{ route('offers.create', $car) }}" class="btn btn-primary-custom btn-lg flex-grow-1">
                                        <i class="bi bi-arrow-left-right me-2"></i>قدم عرض تبديل
                                    </a>
                                @elseif(!$activeCar)
                                    <a href="{{ route('my-cars.create') }}" class="btn btn-warning btn-lg flex-grow-1">
                                        <i class="bi bi-plus-circle me-2"></i>أضف عربيتك أولاً
                                    </a>
                                @elseif(!$car->isAvailable())
                                    <button class="btn btn-secondary btn-lg flex-grow-1" disabled>
                                        <i class="bi bi-x-circle me-2"></i>غير متاحة
                                    </button>
                                @else
                                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-lg flex-grow-1">
                                        <i class="bi bi-clock me-2"></i>انتظر تقييم عربيتك
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary-custom btn-lg flex-grow-1">
                                    <i class="bi bi-box-arrow-in-left me-2"></i>سجل دخول للتبديل
                                </a>
                            @endif
                            <a href="{{ route('cars') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
