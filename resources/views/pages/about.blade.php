@extends('layouts.app')

@section('title', 'من نحن - E7lal.com')

@section('styles')
<style>
    .about-section {
        padding: 80px 0;
        background: #fff;
    }
    
    .about-image {
        position: relative;
    }
    
    .about-image img {
        width: 100%;
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.15);
    }
    
    .about-image::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 20px;
        top: 20px;
        right: 20px;
        z-index: -1;
    }
    
    .about-content h2 {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--dark-color);
        margin-bottom: 25px;
    }
    
    .about-content p {
        color: #666;
        line-height: 1.9;
        font-size: 1.1rem;
        margin-bottom: 20px;
    }
    
    .about-features {
        margin-top: 30px;
    }
    
    .about-feature {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .about-feature i {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    
    .about-feature h4 {
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 5px;
    }
    
    .about-feature p {
        color: #666;
        margin: 0;
        font-size: 1rem;
    }
    
    .stats-box {
        background: linear-gradient(135deg, var(--dark-color), var(--primary-color));
        border-radius: 20px;
        padding: 50px;
        color: #fff;
    }
    
    .stat-item {
        text-align: center;
        padding: 20px;
    }
    
    .stat-item h3 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 10px;
        color: var(--accent-color);
    }
    
    .stat-item p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0;
    }
    
    .vision-section {
        padding: 80px 0;
        background: var(--light-bg);
    }
    
    .vision-card {
        background: #fff;
        border-radius: 20px;
        padding: 40px;
        height: 100%;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .vision-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    
    .vision-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        font-size: 2.5rem;
        color: #fff;
    }
    
    .vision-card h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 15px;
    }
    
    .vision-card p {
        color: #666;
        line-height: 1.8;
    }
    
    .team-section {
        padding: 80px 0;
        background: #fff;
    }
    
    .team-card {
        text-align: center;
        padding: 30px;
    }
    
    .team-avatar {
        width: 150px;
        height: 150px;
        background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 4rem;
        color: #fff;
    }
    
    .team-card h4 {
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 5px;
    }
    
    .team-card .role {
        color: var(--secondary-color);
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .team-card p {
        color: #666;
        font-size: 0.95rem;
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
                <li class="breadcrumb-item active">من نحن</li>
            </ol>
        </nav>
        <h1>من نحن</h1>
        <p>تعرف على قصتنا ورؤيتنا</p>
    </div>
</section>

<!-- About Section -->
<section class="about-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="about-image">
                    <img src="/imgs/homepage.jpg" alt="عن E7lal.com">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-content">
                    <h2>قصتنا</h2>
                    <p>
                        بدأت فكرة E7lal.com من إيماننا بإن تبديل السيارات لازم يكون عملية سهلة وشفافة. 
                        شفنا كتير من الناس بتعاني في بيع عربياتها القديمة وشراء واحدة جديدة، وقررنا نغيّر ده.
                    </p>
                    <p>
                        النهارده، E7lal.com بقت منصة موثوقة لتبديل السيارات في مصر، 
                        بنساعد آلاف العملاء يبدّلوا عربياتهم بكل سهولة وأمان وبأسعار عادلة.
                    </p>
                    
                    <div class="about-features">
                        <div class="about-feature">
                            <i class="bi bi-check-circle"></i>
                            <div>
                                <h4>شفافية كاملة</h4>
                                <p>كل الأسعار واضحة ومفيش رسوم مخفية</p>
                            </div>
                        </div>
                        <div class="about-feature">
                            <i class="bi bi-check-circle"></i>
                            <div>
                                <h4>فريق محترف</h4>
                                <p>خبراء في تقييم السيارات وخدمة العملاء</p>
                            </div>
                        </div>
                        <div class="about-feature">
                            <i class="bi bi-check-circle"></i>
                            <div>
                                <h4>ضمان الجودة</h4>
                                <p>كل العربيات متفحوصة ومضمونة</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Stats -->
        <div class="stats-box mt-5">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <h3>500+</h3>
                        <p>عربية متاحة</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <h3>1000+</h3>
                        <p>عميل سعيد</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <h3>800+</h3>
                        <p>عملية تبديل</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <h3>5</h3>
                        <p>سنوات خبرة</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Vision Section -->
<section class="vision-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">رؤيتنا وقيمنا</h2>
            <p class="section-subtitle">اللي بيميّزنا ويخلينا الاختيار الأول</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="vision-card">
                    <div class="vision-icon">
                        <i class="bi bi-eye"></i>
                    </div>
                    <h3>رؤيتنا</h3>
                    <p>
                        نكون المنصة الأولى والأكبر لتبديل السيارات في مصر والوطن العربي، 
                        ونغيّر طريقة الناس في شراء وبيع السيارات.
                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="vision-card">
                    <div class="vision-icon">
                        <i class="bi bi-bullseye"></i>
                    </div>
                    <h3>مهمتنا</h3>
                    <p>
                        نوفّر تجربة تبديل سيارات سهلة وشفافة وآمنة لكل عملاؤنا، 
                        ونضمنلهم أحسن سعر وأفضل خدمة.
                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="vision-card">
                    <div class="vision-icon">
                        <i class="bi bi-heart"></i>
                    </div>
                    <h3>قيمنا</h3>
                    <p>
                        الصدق والشفافية في كل تعاملاتنا، 
                        احترام العميل، والسعي الدائم للتميز والتطوير.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="team-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">فريقنا</h2>
            <p class="section-subtitle">فريق محترف ومتخصص لخدمتك</p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Hossam Hassan - President -->
            <div class="col-lg-4 col-md-6">
                <div class="team-card">
                    <div class="team-avatar" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
                        ح
                    </div>
                    <h4>حسام حسن</h4>
                    <p class="role">الرئيس التنفيذي</p>
                    <p>القائد المؤسس لمنصة E7lal.com ورؤية التحول الرقمي في سوق السيارات المصري</p>
                </div>
            </div>

            <!-- Mohamed Gamal - Evaluation -->
            <div class="col-lg-4 col-md-6">
                <div class="team-card">
                    <div class="team-avatar" style="background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));">
                        م
                    </div>
                    <h4>محمد جمال</h4>
                    <p class="role">مدير التقييم</p>
                    <p>خبير تقييم السيارات بأعلى معايير الدقة والعدالة في السوق المصري</p>
                </div>
            </div>

            <!-- Ahmed Gooda - Marketing -->
            <div class="col-lg-4 col-md-6">
                <div class="team-card">
                    <div class="team-avatar" style="background: linear-gradient(135deg, var(--accent-color), var(--primary-color));">
                        أ
                    </div>
                    <h4>أحمد جودة</h4>
                    <p class="role">مدير التسويق</p>
                    <p>مسؤول عن بناء العلاقات مع العملاء وتطوير استراتيجية التسويق الرقمي</p>
                </div>
            </div>
        </div>

        <!-- Team Values -->
        <div class="row justify-content-center mt-5">
            <div class="col-lg-10">
                <div class="text-center">
                    <h3 class="fw-bold mb-4" style="color: var(--dark-color);">قيمنا</h3>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="value-card p-4 rounded-3" style="background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                <i class="bi bi-shield-check text-primary mb-3" style="font-size: 2rem;"></i>
                                <h5 class="fw-bold">الثقة والأمانة</h5>
                                <p class="text-muted mb-0">نضمن لك معاملة عادلة وآمنة في جميع تعاملاتك</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="value-card p-4 rounded-3" style="background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                <i class="bi bi-lightning-charge text-warning mb-3" style="font-size: 2rem;"></i>
                                <h5 class="fw-bold">السرعة والكفاءة</h5>
                                <p class="text-muted mb-0">نقدم خدماتنا بأسرع وقت ممكن وبأعلى جودة</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="value-card p-4 rounded-3" style="background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                <i class="bi bi-people text-success mb-3" style="font-size: 2rem;"></i>
                                <h5 class="fw-bold">التركيز على العميل</h5>
                                <p class="text-muted mb-0">رضاك هو أولويتنا الأولى في كل ما نقوم به</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5" style="background: linear-gradient(135deg, var(--dark-color), var(--primary-color));">
    <div class="container text-center text-white">
        <h3 class="mb-3" style="font-size: 2rem; font-weight: 700;">جاهز تبدّل عربيتك؟</h3>
        <p class="mb-4" style="font-size: 1.2rem; opacity: 0.9;">تواصل معانا دلوقتي وابدأ رحلة التبديل</p>
        <a href="{{ route('contact') }}" class="btn btn-accent btn-lg">
            <i class="bi bi-chat-dots me-2"></i>
            تواصل معانا
        </a>
    </div>
</section>
@endsection
