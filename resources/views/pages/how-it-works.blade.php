@extends('layouts.app')

@section('title', 'إزاي بتشتغل؟ - E7lal.com')

@section('styles')
<style>
    .process-section {
        padding: 80px 0;
        background: #fff;
    }
    
    .process-step {
        position: relative;
        padding: 40px;
        text-align: center;
    }
    
    .step-icon {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        font-size: 3rem;
        color: #fff;
        box-shadow: 0 15px 40px rgba(21, 152, 149, 0.3);
    }
    
    .step-num {
        position: absolute;
        top: 20px;
        right: 50%;
        transform: translateX(80px);
        width: 40px;
        height: 40px;
        background: var(--accent-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        color: var(--dark-color);
        font-size: 1.2rem;
    }
    
    .step-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 15px;
    }
    
    .step-desc {
        color: #666;
        line-height: 1.8;
        font-size: 1.1rem;
    }
    
    
    .benefits-section {
        padding: 80px 0;
        background: var(--light-bg);
    }
    
    .benefit-card {
        background: #fff;
        padding: 40px 30px;
        border-radius: 20px;
        text-align: center;
        height: 100%;
        transition: all 0.3s ease;
    }
    
    .benefit-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    
    .benefit-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        font-size: 2rem;
        color: #fff;
    }
    
    .benefit-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 15px;
    }
    
    .faq-section {
        padding: 80px 0;
        background: #fff;
    }
    
    .accordion-item {
        border: none;
        margin-bottom: 15px;
        border-radius: 15px !important;
        overflow: hidden;
        box-shadow: 0 3px 15px rgba(0,0,0,0.05);
    }
    
    .accordion-button {
        font-weight: 600;
        font-size: 1.1rem;
        padding: 20px 25px;
        color: var(--dark-color);
    }
    
    .accordion-button:not(.collapsed) {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: #fff;
    }
    
    .accordion-button:focus {
        box-shadow: none;
    }
    
    .accordion-body {
        padding: 25px;
        color: #666;
        line-height: 1.8;
    }
    
    .cta-box {
        background: linear-gradient(135deg, var(--dark-color), var(--primary-color));
        border-radius: 30px;
        padding: 60px;
        text-align: center;
        color: #fff;
    }
    
    .cta-box h3 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 20px;
    }
    
    .cta-box p {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 30px;
    }
    
    @media (max-width: 768px) {
        
        .step-num {
            right: 20px;
            transform: none;
        }
    }
</style>
@endsection

@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                <li class="breadcrumb-item active">إزاي بتشتغل؟</li>
            </ol>
        </nav>
        <h1>إزاي بتشتغل؟</h1>
        <p>تعرف على خطوات تبديل عربيتك بكل سهولة</p>
    </div>
</section>

<!-- Process Section -->
<section class="process-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">خطوات التبديل</h2>
            <p class="section-subtitle">4 خطوات بسيطة وسهلة لتبديل عربيتك</p>
        </div>
        
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <div class="process-step">
                    <span class="step-num">1</span>
                    <div class="step-icon">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <h3 class="step-title">سجّل بيانات عربيتك</h3>
                    <p class="step-desc">
                        ادخل بيانات عربيتك الحالية زي الماركة والموديل وسنة الصنع والكيلومترات والحالة العامة.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <div class="process-step">
                    <span class="step-num">2</span>
                    <div class="step-icon">
                        <i class="bi bi-search"></i>
                    </div>
                    <h3 class="step-title">تصفّح واختار</h3>
                    <p class="step-desc">
                        شوف العربيات المتاحة للتبديل واختار اللي يناسب احتياجاتك وميزانيتك من التشكيلة الكبيرة.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <div class="process-step">
                    <span class="step-num">3</span>
                    <div class="step-icon">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                    <h3 class="step-title">معاينة وتقييم</h3>
                    <p class="step-desc">
                        فريقنا هييجي يعاين عربيتك ويقيّمها بسعر عادل. كمان هتعاين العربية اللي اخترتها.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="process-step">
                    <span class="step-num">4</span>
                    <div class="step-icon">
                        <i class="bi bi-key"></i>
                    </div>
                    <h3 class="step-title">استلم عربيتك الجديدة</h3>
                    <p class="step-desc">
                        بعد الاتفاق، بنخلص كل الأوراق ونقل الملكية وتستلم عربيتك الجديدة في نفس اليوم.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="benefits-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">ليه تبدّل معانا؟</h2>
            <p class="section-subtitle">مميزات كتير لما تبدّل عربيتك مع E7lal.com</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h3 class="benefit-title">توفير الوقت</h3>
                    <p class="step-desc">
                        مش هتضيع وقتك في البحث والتفاوض. كل حاجة بتتم في مكان واحد وبسرعة.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h3 class="benefit-title">ضمان شامل</h3>
                    <p class="step-desc">
                        كل العربيات متفحوصة وبنديك ضمان عليها. متقلقش من أي مشاكل مخفية.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                    <h3 class="benefit-title">سعر عادل</h3>
                    <p class="step-desc">
                        بنقيّم عربيتك بسعر السوق الحقيقي ومفيش أي رسوم مخفية أو مفاجآت.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <h3 class="benefit-title">أوراق رسمية</h3>
                    <p class="step-desc">
                        بنتولى كل الإجراءات القانونية ونقل الملكية بدون ما تتعب نفسك.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h3 class="benefit-title">دعم مستمر</h3>
                    <p class="step-desc">
                        فريق خدمة العملاء موجود دايماً لمساعدتك قبل وبعد التبديل.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                    <h3 class="benefit-title">تشكيلة متنوعة</h3>
                    <p class="step-desc">
                        عندنا مجموعة كبيرة ومتنوعة من العربيات لكل الميزانيات والاحتياجات.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">أسئلة شائعة</h2>
            <p class="section-subtitle">إجابات على أكتر الأسئلة اللي بتيجيلنا</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                إيه الأوراق المطلوبة للتبديل؟
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                هتحتاج رخصة العربية الأصلية، البطاقة الشخصية، وتوكيل رسمي لو العربية مش باسمك. فريقنا هيساعدك في كل الإجراءات.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                كام يوم بياخد التبديل؟
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                في الغالب، عملية التبديل بتتم في خلال 24 لـ 48 ساعة من المعاينة لحد استلام العربية الجديدة. ممكن تاخد وقت أكتر لو في إجراءات إضافية.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                لو في فرق سعر، بدفع إزاي؟
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                لو العربية الجديدة سعرها أعلى، ممكن تدفع الفرق كاش أو بالتقسيط. ولو عربيتك سعرها أعلى، هنديك الفرق نقداً.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                إيه الضمان اللي بتقدموه؟
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                بنقدم ضمان على كل العربيات يشمل المحرك وناقل الحركة والأجزاء الأساسية. مدة الضمان بتختلف حسب العربية وحالتها.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                ينفع أبدّل عربية عليها أقساط؟
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                أيوه، ينفع. بنتعامل مع البنوك وشركات التمويل لتسوية الأقساط المتبقية. تواصل معانا وهنشرحلك التفاصيل.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5">
    <div class="container">
        <div class="cta-box">
            <h3>جاهز تبدأ؟</h3>
            <p>ابدأ دلوقتي واحصل على تقييم مجاني لعربيتك</p>
            <a href="{{ route('cars') }}" class="btn btn-accent btn-lg">
                <i class="bi bi-car-front-fill me-2"></i>
                شوف العربيات المتاحة
            </a>
        </div>
    </div>
</section>
@endsection
