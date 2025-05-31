<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'دليل المتاجر المحلية') }}</title>
    
    <!-- Bootstrap RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #343a40;
            color: white;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
        }
        
        .sidebar .nav-link:hover {
            color: white;
        }
        
        .sidebar .nav-link.active {
            color: white;
            background-color: #212529;
        }
        
        .main-content {
            padding: 20px;
        }
        
        .card {
            margin-bottom: 20px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">دليل المتاجر المحلية</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('stores.index') }}">المتاجر</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            الفئات
                        </a>
                        <ul class="dropdown-menu">
                            @foreach(\App\Models\Category::all() as $category)
                                <li><a class="dropdown-item" href="{{ route('stores.index', ['category' => $category->id]) }}">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">تسجيل الدخول</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">حساب جديد</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                                <li><a class="dropdown-item" href="{{ route('favorites.index') }}">المفضلة</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        تسجيل الخروج
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            @auth
                @if(request()->routeIs('dashboard*'))
                    <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                        <div class="position-sticky pt-3">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{ route('dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>
                                        لوحة التحكم
                                    </a>
                                </li>
                                
                                @if(Auth::user()->role === 'admin')
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('categories.index') }}">
                                            <i class="fas fa-tags me-2"></i>
                                            إدارة الفئات
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">
                                            <i class="fas fa-store me-2"></i>
                                            إدارة المتاجر
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">
                                            <i class="fas fa-users me-2"></i>
                                            إدارة المستخدمين
                                        </a>
                                    </li>
                                @elseif(Auth::user()->role === 'vendor')
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">
                                            <i class="fas fa-store me-2"></i>
                                            متجري
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">
                                            <i class="fas fa-box me-2"></i>
                                            المنتجات
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">
                                            <i class="fas fa-star me-2"></i>
                                            التقييمات
                                        </a>
                                    </li>
                                @else
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('favorites.index') }}">
                                            <i class="fas fa-heart me-2"></i>
                                            المفضلة
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">
                                            <i class="fas fa-star me-2"></i>
                                            تقييماتي
                                        </a>
                                    </li>
                                @endif
                                
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="fas fa-user-cog me-2"></i>
                                        إعدادات الحساب
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                        @yield('content')
                    </main>
                @else
                    <main class="col-12 main-content">
                        @yield('content')
                    </main>
                @endif
            @else
                <main class="col-12 main-content">
                    @yield('content')
                </main>
            @endauth
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>