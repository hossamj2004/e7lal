<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E7lal.com - بدّل عربيتك</title>
    
    <!-- Bootstrap RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    <!-- Google Fonts - Cairo for Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #1a5f7a;
            --secondary-color: #159895;
            --accent-color: #57c5b6;
            --dark-color: #0b2447;
            --light-bg: #f8f9fa;
        }
        
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        body {
            background-color: var(--light-bg);
        }
        
        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, var(--dark-color) 0%, var(--primary-color) 100%);
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        .navbar-brand img {
            height: 55px;
            border-radius: 8px;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1.2rem !important;
            transition: all 0.3s ease;
            border-radius: 8px;
        }
        
        .nav-link:hover, .nav-link.active {
            color: #fff !important;
            background: rgba(255,255,255,0.1);
        }
        
        .btn-navbar {
            background: var(--accent-color);
            color: var(--dark-color);
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 25px;
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-navbar:hover {
            background: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--dark-color) 0%, var(--primary-color) 50%, var(--secondary-color) 100%);
            min-height: 85vh;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('/imgs/homepage.jpg') center center;
            background-size: cover;
            opacity: 0.15;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            padding: 100px 0;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 1.5rem;
            line-height: 1.3;
        }
        
        .hero-subtitle {
            font-size: 1.4rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 2.5rem;
            line-height: 1.8;
        }
        
        .btn-hero {
            background: var(--accent-color);
            color: var(--dark-color);
            font-weight: 700;
            padding: 1rem 2.5rem;
            font-size: 1.2rem;
            border-radius: 50px;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(87, 197, 182, 0.4);
        }
        
        .btn-hero:hover {
            background: #fff;
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(87, 197, 182, 0.5);
        }
        
        .btn-hero-outline {
            background: transparent;
            color: #fff;
            font-weight: 600;
            padding: 1rem 2.5rem;
            font-size: 1.2rem;
            border-radius: 50px;
            border: 2px solid rgba(255,255,255,0.5);
            margin-right: 1rem;
            transition: all 0.3s ease;
        }
        
        .btn-hero-outline:hover {
            background: rgba(255,255,255,0.1);
            border-color: #fff;
            color: #fff;
        }
        
        .hero-image {
            position: relative;
            z-index: 2;
        }
        
        .hero-image img {
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.3);
        }
        
        /* Stats Section */
        .stats-section {
            background: #fff;
            padding: 60px 0;
            margin-top: -50px;
            position: relative;
            z-index: 3;
            border-radius: 30px 30px 0 0;
        }
        
        .stat-card {
            text-align: center;
            padding: 30px 20px;
            border-radius: 15px;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: #fff;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark-color);
        }
        
        .stat-label {
            color: #666;
            font-size: 1.1rem;
        }
        
        /* Features Section */
        .features-section {
            padding: 100px 0;
            background: #fff;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark-color);
            margin-bottom: 1rem;
        }
        
        .section-subtitle {
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 3rem;
        }
        
        .feature-card {
            background: var(--light-bg);
            padding: 40px 30px;
            border-radius: 20px;
            text-align: center;
            height: 100%;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .feature-card:hover {
            border-color: var(--accent-color);
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 2.5rem;
            color: #fff;
        }
        
        .feature-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 15px;
        }
        
        .feature-text {
            color: #666;
            line-height: 1.8;
        }
        
        /* How It Works */
        .how-it-works {
            padding: 100px 0;
            background: linear-gradient(180deg, var(--light-bg) 0%, #fff 100%);
        }
        
        .step-card {
            position: relative;
            padding: 30px;
            text-align: center;
        }
        
        .step-number {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 1.5rem;
            font-weight: 800;
            color: #fff;
        }
        
        .step-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 15px;
        }
        
        .step-text {
            color: #666;
            line-height: 1.7;
        }
        
        
        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--dark-color) 0%, var(--primary-color) 100%);
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }
        
        .cta-section::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: var(--accent-color);
            border-radius: 50%;
            top: -200px;
            left: -200px;
            opacity: 0.1;
        }
        
        .cta-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 1.5rem;
        }
        
        .cta-text {
            color: rgba(255,255,255,0.9);
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        
        /* Footer */
        .footer {
            background: var(--dark-color);
            padding: 60px 0 30px;
            color: rgba(255,255,255,0.8);
        }
        
        .footer-logo img {
            height: 50px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        
        .footer-text {
            color: rgba(255,255,255,0.7);
            line-height: 1.8;
            margin-top: 15px;
        }
        
        .footer-title {
            color: #fff;
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 25px;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 12px;
        }
        
        .footer-links a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer-links a:hover {
            color: var(--accent-color);
            padding-right: 10px;
        }
        
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            color: #fff;
            margin-left: 10px;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: var(--accent-color);
            transform: translateY(-3px);
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 30px;
            margin-top: 40px;
            text-align: center;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .btn-hero, .btn-hero-outline {
                padding: 0.8rem 1.5rem;
                font-size: 1rem;
                display: block;
                width: 100%;
                margin-bottom: 15px;
            }
            
            .btn-hero-outline {
                margin-right: 0;
            }
            
            
            .section-title {
                font-size: 1.8rem;
            }
        }

    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="/imgs/logo.png" alt="E7lal.com Logo">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border-color: rgba(255,255,255,0.5);">
                <i class="bi bi-list text-white fs-2"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('home') }}">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cars') }}">العربيات المتاحة</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('how-it-works') }}">إزاي بتشتغل؟</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">من نحن</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">تواصل معانا</a>
                    </li>
                </ul>
                <a href="{{ route('cars') }}" class="btn btn-navbar">ابدأ دلوقتي</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h1 class="hero-title">
                        بدّل عربيتك القديمة
                        <br>
                        <span style="color: var(--accent-color);">بأحسن سعر</span>
                    </h1>
                    <p class="hero-subtitle">
                        مع E7lal.com، تقدر تبدّل عربيتك بعربية أحدث وأحسن بأسعار عادلة وشفافة. 
                        عملية سهلة وسريعة من غير أي تعقيدات!
                    </p>
                    <div class="d-flex flex-wrap">
                        <a href="{{ route('cars') }}" class="btn btn-hero">
                            <i class="bi bi-car-front-fill me-2"></i>
                            بدّل عربيتك
                        </a>
                        <a href="{{ route('how-it-works') }}" class="btn btn-hero-outline">
                            <i class="bi bi-play-circle me-2"></i>
                            شوف إزاي
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image">
                        <img src="/imgs/homepage.jpg" alt="تبديل السيارات">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-car-front"></i>
                        </div>
                        <div class="stat-number">500+</div>
                        <div class="stat-label">عربية متاحة</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">عميل سعيد</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-arrow-left-right"></i>
                        </div>
                        <div class="stat-number">800+</div>
                        <div class="stat-label">عملية تبديل</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <div class="stat-number">4.9</div>
                        <div class="stat-label">تقييم العملاء</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">ليه تختار E7lal.com؟</h2>
                <p class="section-subtitle">نقدملك خدمة مميزة وسهلة لتبديل عربيتك</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <h3 class="feature-title">أسعار عادلة</h3>
                        <p class="feature-text">
                            بنقيّم عربيتك بسعر السوق الحقيقي ونديك أحسن عرض ممكن. 
                            مفيش رسوم مخفية أو مفاجآت.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3 class="feature-title">ضمان وأمان</h3>
                        <p class="feature-text">
                            كل العربيات عندنا متفحوصة ومضمونة. 
                            بنوفرلك ضمان على كل عربية بتشتريها.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-lightning-charge"></i>
                        </div>
                        <h3 class="feature-title">سرعة في التنفيذ</h3>
                        <p class="feature-text">
                            عملية التبديل بتتم في وقت قياسي. 
                            من المعاينة للتسليم في أقل من 48 ساعة.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <h3 class="feature-title">أوراق قانونية</h3>
                        <p class="feature-text">
                            بنتولى كل الإجراءات القانونية ونقل الملكية. 
                            مش هتحتاج تعمل أي حاجة بنفسك.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-car-front-fill"></i>
                        </div>
                        <h3 class="feature-title">تشكيلة كبيرة</h3>
                        <p class="feature-text">
                            عندنا مجموعة كبيرة من العربيات المتاحة للتبديل. 
                            هتلاقي اللي يناسبك بالظبط.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-headset"></i>
                        </div>
                        <h3 class="feature-title">دعم مستمر</h3>
                        <p class="feature-text">
                            فريق خدمة العملاء موجود دايماً لمساعدتك. 
                            أي سؤال أو استفسار، احنا هنا.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">إزاي بنشتغل؟</h2>
                <p class="section-subtitle">4 خطوات بسيطة وهتبدّل عربيتك</p>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h4 class="step-title">سجّل عربيتك</h4>
                        <p class="step-text">
                            ادخل بيانات عربيتك الحالية والحالة بتاعتها عشان نقدر نقيّمها صح.
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h4 class="step-title">اختار عربيتك الجديدة</h4>
                        <p class="step-text">
                            تصفّح العربيات المتاحة واختار اللي يناسب احتياجاتك وميزانيتك.
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h4 class="step-title">معاينة وتقييم</h4>
                        <p class="step-text">
                            هنيجي نعاين عربيتك ونقيّمها بسعر عادل ومنصف للطرفين.
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h4 class="step-title">استلم عربيتك الجديدة</h4>
                        <p class="step-text">
                            بعد الموافقة، هننقل الملكية وتستلم عربيتك الجديدة فوراً.
                        </p>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
                <a href="{{ route('how-it-works') }}" class="btn btn-hero">
                    <i class="bi bi-info-circle me-2"></i>
                    اعرف أكتر
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h2 class="cta-title">جاهز تبدّل عربيتك؟</h2>
                    <p class="cta-text">
                        ابدأ دلوقتي واحصل على تقييم مجاني لعربيتك. 
                        مش هتندم!
                    </p>
                    <a href="{{ route('cars') }}" class="btn btn-hero">
                        <i class="bi bi-rocket-takeoff me-2"></i>
                        ابدأ التبديل مجاناً
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-logo">
                        <img src="/imgs/logo.png" alt="E7lal.com">
                    </div>
                    <p class="footer-text">
                        منصة تبديل السيارات الأولى في مصر. 
                        بنساعدك تبدّل عربيتك القديمة بأحدث وأحسن بأسعار عادلة.
                    </p>
                    <div class="social-links mt-4">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter-x"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <h5 class="footer-title">روابط سريعة</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">الرئيسية</a></li>
                        <li><a href="{{ route('cars') }}">العربيات المتاحة</a></li>
                        <li><a href="{{ route('about') }}">من نحن</a></li>
                        <li><a href="{{ route('contact') }}">تواصل معانا</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <h5 class="footer-title">خدماتنا</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('cars') }}">تبديل السيارات</a></li>
                        <li><a href="{{ route('contact') }}">تقييم مجاني</a></li>
                        <li><a href="{{ route('how-it-works') }}">إزاي بتشتغل؟</a></li>
                        <li><a href="{{ route('about') }}">ضمان السيارات</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-title">تواصل معانا</h5>
                    <ul class="footer-links">
                        <li>
                            <i class="bi bi-geo-alt me-2 text-info"></i>
                            القاهرة، مصر
                        </li>
                        <li>
                            <i class="bi bi-telephone me-2 text-info"></i>
                            01220437090
                        </li>
                        <li>
                            <i class="bi bi-envelope me-2 text-info"></i>
                            info@e7lal.com
                        </li>
                        <li>
                            <i class="bi bi-clock me-2 text-info"></i>
                            السبت - الخميس: 9ص - 9م
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="mb-0">
                    © 2024 E7lal.com - جميع الحقوق محفوظة
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
