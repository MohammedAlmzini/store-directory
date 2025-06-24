@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">متجري</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('stores.edit', $store) }}" class="btn btn-sm btn-outline-primary me-2">
                <i class="fas fa-edit"></i> تعديل المتجر
            </a>
            <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> إضافة منتج جديد
            </a>
        </div>
    </div>

    @if($store->status !== 'approved')
        <div class="alert alert-{{ $store->status === 'pending' ? 'warning' : 'danger' }}">
            <h5>حالة المتجر: {{ $store->status === 'pending' ? 'قيد المراجعة' : 'مرفوض' }}</h5>
            <p>
                @if($store->status === 'pending')
                    متجرك قيد المراجعة من قبل الإدارة. سيتم إخطارك بمجرد الموافقة عليه.
                @else
                    تم رفض متجرك من قبل الإدارة. يرجى مراجعة معلومات المتجر وتحديثها.
                @endif
            </p>
        </div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    @if($store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="img-fluid rounded-circle my-3" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="bg-secondary text-white rounded-circle d-inline-flex justify-content-center align-items-center my-3" style="width: 150px; height: 150px;">
                            <i class="fas fa-store fa-4x"></i>
                        </div>
                    @endif
                    
                    <h3 class="card-title">{{ $store->name }}</h3>
                    
                    <div class="d-flex justify-content-center mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star fa-lg {{ $i <= $store->average_rating ? 'text-warning' : 'text-secondary' }}"></i>
                        @endfor
                        <span class="ms-2">({{ $reviews->count() }} تقييم)</span>
                    </div>
                    
                    @if($store->category)
                        <p class="text-muted">
                            <i class="fas fa-tag"></i> {{ $store->category->name }}
                        </p>
                    @endif
                    
                    @if($store->city)
                        <p class="text-muted">
                            <i class="fas fa-map-marker-alt"></i> {{ $store->city }}
                        </p>
                    @endif
                    
                    @if($store->contact_info)
                        <p class="text-muted">
                            <i class="fas fa-phone"></i> {{ $store->contact_info }}
                        </p>
                    @endif
                    
                    <p class="card-text">
                        {{ $store->description }}
                    </p>
                    
                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('stores.show', $store) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> عرض صفحة المتجر العامة
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">أحدث التقييمات</h5>
                    <a href="{{ route('vendor.reviews') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                </div>
                <div class="card-body">
                    @if(count($reviews) > 0)
                        @foreach($reviews->take(5) as $review)
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-0">{{ $review->user->name }}</h6>
                                        <div class="text-muted small">{{ $review->created_at->format('Y-m-d') }}</div>
                                    </div>
                                    <div>
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                
                                @if($review->comment)
                                    <p class="mt-2 mb-0">{{ $review->comment }}</p>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-center text-muted">لا توجد تقييمات لهذا المتجر حتى الآن.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">منتجات المتجر</h5>
                    <a href="{{ route('vendor.products') }}" class="btn btn-sm btn-outline-primary">إدارة المنتجات</a>
                </div>
                <div class="card-body">
                    @if(count($products) > 0)
                        <div class="row">
                            @foreach($products as $product)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        <div class="text-center pt-3">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid" style="height: 150px; object-fit: contain;">
                                            @else
                                                <div class="bg-secondary text-white d-flex justify-content-center align-items-center" style="height: 150px;">
                                                    <i class="fas fa-box fa-3x"></i>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $product->name }}</h5>
                                            <p class="card-text text-primary fw-bold">{{ $product->price }} ₪</p>
                                            
                                            @if($product->category)
                                                <p class="card-text text-muted small">
                                                    <i class="fas fa-tag"></i> {{ $product->category->name }}
                                                </p>
                                            @endif
                                            
                                            <p class="card-text small">
                                                {{ Str::limit($product->description, 100) }}
                                            </p>
                                            
                                            <div class="d-grid gap-2">
                                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i> تعديل
                                                </a>
                                                
                                                <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100" onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                                        <i class="fas fa-trash"></i> حذف
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="mb-0">لم تقم بإضافة أي منتجات حتى الآن.</p>
                            <a href="{{ route('products.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus"></i> إضافة منتج جديد
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">إحصائيات المتجر</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card bg-primary text-white h-100">
                                <div class="card-body text-center">
                                    <h1 class="display-4">{{ count($products) }}</h1>
                                    <p class="mb-0">المنتجات</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body text-center">
                                    <h1 class="display-4">{{ count($reviews) }}</h1>
                                    <p class="mb-0">التقييمات</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-info text-white h-100">
                                <div class="card-body text-center">
                                    <h1 class="display-4">
                                        {{ count($reviews) > 0 ? round($reviews->avg('rating'), 1) : '0' }}
                                    </h1>
                                    <p class="mb-0">متوسط التقييم</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection