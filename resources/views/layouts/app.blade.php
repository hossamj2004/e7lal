<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'E7lal.com - بدّل عربيتك')</title>

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

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, var(--dark-color) 0%, var(--primary-color) 100%);
            padding: 120px 0 60px;
            margin-bottom: 0;
        }

        .page-header h1 {
            color: #fff;
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .page-header p {
            color: rgba(255,255,255,0.8);
            font-size: 1.2rem;
        }

        .breadcrumb {
            background: transparent;
            margin-bottom: 0;
        }

        .breadcrumb-item a {
            color: var(--accent-color);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: rgba(255,255,255,0.7);
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(255,255,255,0.5);
        }

        /* Section Styles */
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

        /* Cards */
        .custom-card {
            background: #fff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
        }

        .custom-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }

        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: #fff;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 50px;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(21, 152, 149, 0.4);
            color: #fff;
        }

        .btn-accent {
            background: var(--accent-color);
            color: var(--dark-color);
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 50px;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-accent:hover {
            background: var(--secondary-color);
            color: #fff;
            transform: translateY(-2px);
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
            .page-header h1 {
                font-size: 1.8rem;
            }

            .section-title {
                font-size: 1.8rem;
            }
        }

        @yield('styles')
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
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('cars') ? 'active' : '' }}" href="{{ route('cars') }}">العربيات المتاحة</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('how-it-works') ? 'active' : '' }}" href="{{ route('how-it-works') }}">إزاي بتشتغل؟</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">من نحن</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">تواصل معانا</a>
                    </li>
                </ul>
                @auth
                    <div class="d-flex align-items-center gap-2">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-navbar">لوحة التحكم</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="btn btn-navbar">حسابي</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light">خروج</button>
                        </form>
                    </div>
                @else
                    <div class="d-flex gap-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-light">دخول</a>
                        <a href="{{ route('register-now') }}" class="btn btn-navbar">سجل الان</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    @yield('content')

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
                        <li><a href="{{ route('how-it-works') }}">إزاي بتشتغل؟</a></li>
                        <li><a href="{{ route('contact') }}">تقييم مجاني</a></li>
                        <li><a href="{{ route('about') }}">ضمان السيارات</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-title">تواصل معانا</h5>
                    <ul class="footer-links">
                        <li>
                            <i class="bi bi-geo-alt me-2 text-info"></i>
                            القطامية بجوار بنك الاسكان و التعمير
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
    @yield('scripts')
</body>
</html>
