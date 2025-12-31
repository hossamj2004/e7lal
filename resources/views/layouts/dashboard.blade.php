<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'لوحة التحكم - E7lal.com')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #1a5f7a;
            --secondary-color: #159895;
            --accent-color: #57c5b6;
            --dark-color: #0b2447;
            --light-bg: #f8f9fa;
            --sidebar-width: 280px;
        }

        * { font-family: 'Cairo', sans-serif; }
        body { background-color: var(--light-bg); }

        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--dark-color), var(--primary-color));
            padding: 20px;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-logo {
            padding: 20px 0;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }

        .sidebar-logo img { height: 50px; border-radius: 8px; }

        .sidebar-menu { list-style: none; padding: 0; margin: 0; }

        .sidebar-menu li { margin-bottom: 5px; }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

        .sidebar-menu a i { font-size: 1.2rem; }

        .sidebar-section {
            color: rgba(255,255,255,0.5);
            font-size: 0.85rem;
            padding: 15px 20px 5px;
            text-transform: uppercase;
        }

        .main-content {
            margin-right: var(--sidebar-width);
            min-height: 100vh;
        }

        .top-bar {
            background: #fff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .page-content { padding: 30px; }

        .stat-card {
            background: #fff;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #fff;
        }

        .table-card {
            background: #fff;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 25px;
            font-weight: 600;
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
            color: #fff;
        }

        @media (max-width: 992px) {
            .sidebar { transform: translateX(100%); transition: transform 0.3s; }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-right: 0; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <img src="/imgs/logo.png" alt="E7lal.com">
        </div>

        <ul class="sidebar-menu">
            @if(auth()->user()->isAdmin())
                {{-- Admin Menu --}}
                <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> لوحة التحكم
                </a></li>

                <div class="sidebar-section">إدارة السيارات</div>
                <li><a href="{{ route('admin.cars.index') }}" class="{{ request()->routeIs('admin.cars.*') ? 'active' : '' }}">
                    <i class="bi bi-car-front"></i> السيارات المتاحة
                </a></li>
                <li><a href="{{ route('admin.user-cars.pending') }}" class="{{ request()->routeIs('admin.user-cars.pending') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> سيارات تحتاج تسعير
                </a></li>
                <li><a href="{{ route('admin.user-cars.index') }}" class="{{ request()->routeIs('admin.user-cars.index') ? 'active' : '' }}">
                    <i class="bi bi-list-ul"></i> كل سيارات المستخدمين
                </a></li>

                <div class="sidebar-section">العروض</div>
                <li><a href="{{ route('admin.offers.pending') }}" class="{{ request()->routeIs('admin.offers.pending') ? 'active' : '' }}">
                    <i class="bi bi-bell"></i> عروض جديدة
                </a></li>
                <li><a href="{{ route('admin.offers.index') }}" class="{{ request()->routeIs('admin.offers.index') ? 'active' : '' }}">
                    <i class="bi bi-collection"></i> كل العروض
                </a></li>

                <div class="sidebar-section">طلبات التبديل</div>
                <li><a href="{{ route('admin.exchange-requests.index') }}" class="{{ request()->routeIs('admin.exchange-requests.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right"></i> إدارة الطلبات
                </a></li>

                <div class="sidebar-section">المستخدمين</div>
                <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> إدارة المستخدمين
                </a></li>

                <div class="sidebar-section">النظام</div>
                <li><a href="{{ route('admin.database.index') }}" class="{{ request()->routeIs('admin.database.*') ? 'active' : '' }}">
                    <i class="bi bi-database"></i> إدارة قاعدة البيانات
                </a></li>

                <div class="sidebar-section">الخروج</div>
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="bi bi-house"></i> الصفحة الرئيسية
                </a></li>
            @else
                {{-- User Menu --}}
                <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> الرئيسية
                </a></li>

                <div class="sidebar-section">سياراتي</div>
                <li><a href="{{ route('my-cars.index') }}" class="{{ request()->routeIs('my-cars.*') ? 'active' : '' }}">
                    <i class="bi bi-car-front"></i> سياراتي
                </a></li>
                <li><a href="{{ route('my-cars.create') }}">
                    <i class="bi bi-plus-circle"></i> أضف سيارة
                </a></li>

                <div class="sidebar-section">العروض</div>
                <li><a href="{{ route('offers.index') }}" class="{{ request()->routeIs('offers.*') ? 'active' : '' }}">
                    <i class="bi bi-send"></i> عروضي
                </a></li>
                <li><a href="{{ route('cars') }}">
                    <i class="bi bi-search"></i> تصفح السيارات
                </a></li>

                <div class="sidebar-section">الخروج</div>
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="bi bi-house"></i> الصفحة الرئيسية
                </a></li>
            @endif
        </ul>

        <div style="position: absolute; bottom: 20px; right: 20px; left: 20px;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger w-100">
                    <i class="bi bi-box-arrow-right me-2"></i> تسجيل الخروج
                </button>
            </form>
        </div>
    </aside>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Main Content -->
    <main class="main-content">
        <div class="top-bar">
            <h5 class="mb-0 fw-bold" style="color: white;">@yield('page-title', 'لوحة التحكم')</h5>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted">أهلاً، {{ auth()->user()->name }}</span>
            </div>
        </div>

        <div class="page-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>


    @yield('scripts')
</body>
</html>
