@extends('layouts.app')

@section('title', 'سجل الان - E7lal.com')

@section('content')
<!-- Page Header -->
<section class="page-header" style="padding-top: 120px; padding-bottom: 60px;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="fw-bold" style="color: white; font-size: 2.5rem;">سجل الان</h1>
                <p class="text-muted" style="color: white !important; font-size: 1.2rem;">
                    ابدأ رحلتك في تبديل عربيتك بأحسن سعر
                </p>
            </div>
            <div class="col-lg-6 text-center">
                <i class="bi bi-car-front-fill" style="font-size: 8rem; color: rgba(255,255,255,0.3);"></i>
            </div>
        </div>
    </div>
</section>

<!-- Exchange Form Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="h3 fw-bold text-dark mb-3">ابدأ طلب التبديل</h2>
                            <p class="text-muted">اكتب بيانات سيارتك وسنتواصل معك</p>
                        </div>

                        <form action="{{ route('submit-exchange-request') }}" method="POST" class="exchange-form">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="phone" class="form-label fw-semibold">
                                        <i class="bi bi-whatsapp me-2 text-success"></i>
                                        رقم تليفونك ؟ <span class="text-danger">*</span>
                                    </label>
                                    <input type="tel" class="form-control form-control-lg" id="phone" name="phone" placeholder="01220437090" required>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="car_model" class="form-label fw-semibold">
                                        <i class="bi bi-car-front me-2 text-primary"></i>
                                        عربيتك نوعها ايه؟ <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="car_model" name="car_model" placeholder="هيونداي فيرنا 2010" required>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="desired_price_range" class="form-label fw-semibold">
                                        <i class="bi bi-graph-up me-2 text-info"></i>
                                        بتدور علي عربية بكام؟
                                    </label>
                                    <select class="form-control form-control-lg" id="desired_price_range" name="desired_price_range">
                                        <option value="">اختر الرينج المطلوب</option>
                                        <option value="50-200">50–200 ألف</option>
                                        <option value="200-300">200–300 ألف</option>
                                        <option value="300-400">300–400 ألف</option>
                                        <option value="400-500">400–500 ألف</option>
                                        <option value="500+">أكثر من 500 ألف</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="location" class="form-label fw-semibold">
                                        <i class="bi bi-geo-alt me-2 text-warning"></i>
                                        في أي منطقة عربيتك؟ <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="location" name="location" placeholder="مدينة نصر" required>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5 py-3 fw-bold" style="border-radius: 50px;">
                                    <i class="bi bi-send me-2"></i>
                                    احصل على عروض التبديل
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

                <!-- Features -->
                <div class="row mt-5 g-4">
                    <div class="col-md-4 text-center">
                        <div class="p-3">
                            <i class="bi bi-shield-check text-success" style="font-size: 2rem;"></i>
                            <h6 class="mt-2 fw-bold">آمن ومضمون</h6>
                            <p class="text-muted small">جميع المعاملات محمية وضمان شامل</p>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="p-3">
                            <i class="bi bi-lightning-charge text-warning" style="font-size: 2rem;"></i>
                            <h6 class="mt-2 fw-bold">سرعة في التنفيذ</h6>
                            <p class="text-muted small">معاينة وتسليم في أقل من 48 ساعة</p>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="p-3">
                            <i class="bi bi-cash-coin text-primary" style="font-size: 2rem;"></i>
                            <h6 class="mt-2 fw-bold">أسعار عادلة</h6>
                            <p class="text-muted small">تقييم دقيق وسعر عادل لعربيتك</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
