@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <h1 class="mb-4">استكشف المتاجر</h1>
    
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-body">
                    <form method="GET" action="{{ route('stores.index') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="ابحث عن متجر..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="category" class="form-select">
                                    <option value="">جميع الفئات</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="city" class="form-select">
                                    <option value="all">جميع المدن</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->city }}" {{ request('city') == $city->city ? 'selected' : '' }}>
                                            {{ $city->city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> بحث
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        @if(count($stores) > 0)
            @foreach($stores as $store)
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="text-center mt-3">
                            @if($store->logo)
                                <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="img-fluid rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="bg-secondary text-white rounded-circle d-inline-flex justify-content-center align-items-center" style="width: 100px; height: 100px;">
                                    <i class="fas fa-store fa-3x"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $store->name }}</h5>
                            
                            @if($store->city)
                                <p class="text-muted">
                                    <i class="fas fa-map-marker-alt"></i> {{ $store->city }}
                                </p>
                            @endif
                            
                            <div class="mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $store->average_rating ? 'text-warning' : 'text-secondary' }}"></i>
                                @endfor
                                <span class="text-muted">({{ $store->reviews->count() }})</span>
                            </div>
                            
                            <p class="card-text small">
                                {{ Str::limit($store->description, 100) }}
                            </p>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('stores.show', $store) }}" class="btn btn-primary">
                                    <i class="fas fa-eye"></i> عرض المتجر
                                </a>
                                
                                @auth
                                    @if(Auth::user()->favorites()->where('store_id', $store->id)->exists())
                                        <form method="POST" action="{{ route('favorites.remove', $store) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger w-100">
                                                <i class="fas fa-heart"></i> إزالة من المفضلة
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('favorites.add', $store) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary w-100">
                                                <i class="far fa-heart"></i> إضافة للمفضلة
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <div class="d-flex justify-content-center mt-4">
                {{ $stores->appends(request()->query())->links() }}
            </div>
        @else
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <h4 class="alert-heading">لا توجد متاجر!</h4>
                    <p>لم يتم العثور على متاجر مطابقة لمعايير البحث. جرب معايير بحث مختلفة.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection