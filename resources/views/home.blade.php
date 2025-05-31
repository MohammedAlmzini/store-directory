@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="jumbotron bg-light p-5 rounded-3 text-center mb-4">
                <h1 class="display-4">مرحباً بك في منصة المتاجر الإلكترونية</h1>
                <p class="lead">استكشف أفضل المتاجر المحلية الإلكترونية في مكان واحد</p>
                <hr class="my-4">
                <p>يمكنك تصفح المتاجر حسب الفئات المختلفة، تقييمها، والتفاعل معها.</p>
                <a class="btn btn-primary btn-lg" href="{{ route('stores.index') }}" role="button">استكشف المتاجر</a>
                
                @guest
                    <div class="mt-3">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">تسجيل الدخول</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-success">إنشاء حساب</a>
                    </div>
                @endguest
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">استكشف الفئات</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach(\App\Models\Category::all() as $category)
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="{{ route('stores.index', ['category' => $category->id]) }}" class="text-decoration-none">
                                    <div class="card text-center h-100 shadow-sm">
                                        <div class="card-body">
                                            <i class="fas fa-tag fa-2x text-primary mb-2"></i>
                                            <h5 class="card-title">{{ $category->name }}</h5>
                                            <p class="card-text text-muted small">
                                                {{ \App\Models\Store::where('category_id', $category->id)->where('status', 'approved')->count() }} متجر
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">المتاجر المميزة</h4>
                    <a href="{{ route('stores.index') }}" class="btn btn-sm btn-light">عرض الكل</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach(\App\Models\Store::where('status', 'approved')->orderBy('created_at', 'desc')->take(4)->get() as $store)
                            <div class="col-md-3 mb-3">
                                <div class="card h-100 shadow-sm">
                                    <div class="text-center mt-3">
                                        @if($store->logo)
                                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="img-fluid rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary text-white rounded-circle d-inline-flex justify-content-center align-items-center" style="width: 80px; height: 80px;">
                                                <i class="fas fa-store fa-2x"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $store->name }}</h5>
                                        
                                        @if($store->city)
                                            <p class="text-muted small">
                                                <i class="fas fa-map-marker-alt"></i> {{ $store->city }}
                                            </p>
                                        @endif
                                        
                                        <div class="mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $store->average_rating ? 'text-warning' : 'text-secondary' }}"></i>
                                            @endfor
                                        </div>
                                        
                                        <a href="{{ route('stores.show', $store) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> عرض المتجر
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">كيف تعمل المنصة؟</h4>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="bg-light p-4 rounded-3 h-100">
                                <i class="fas fa-search fa-3x text-primary mb-3"></i>
                                <h5>استكشف المتاجر</h5>
                                <p>تصفح المتاجر المحلية حسب الفئات أو المدن واكتشف منتجاتهم.</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="bg-light p-4 rounded-3 h-100">
                                <i class="fas fa-star fa-3x text-warning mb-3"></i>
                                <h5>قيّم المتاجر</h5>
                                <p>شارك تجربتك مع الآخرين من خلال تقييم المتاجر والتعليق عليها.</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="bg-light p-4 rounded-3 h-100">
                                <i class="fas fa-store fa-3x text-success mb-3"></i>
                                <h5>أنشئ متجرك الخاص</h5>
                                <p>هل أنت صاحب متجر؟ سجّل الآن وأضف متجرك إلى المنصة لزيادة ظهورك.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection