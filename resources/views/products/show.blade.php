@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">تفاصيل المنتج</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            @if(Auth::id() == $product->store->user_id)
                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-primary me-2">
                    <i class="fas fa-edit"></i> تعديل المنتج
                </a>
                <a href="{{ route('vendor.products') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-right"></i> العودة لقائمة المنتجات
                </a>
            @else
                <a href="{{ route('stores.show', $product->store) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-store"></i> عرض المتجر
                </a>
            @endif
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded mb-3" style="max-height: 300px;">
                    @else
                        <div class="bg-secondary text-white d-flex justify-content-center align-items-center rounded mb-3" style="height: 300px;">
                            <i class="fas fa-box fa-5x"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">معلومات المنتج</h6>
                </div>
                <div class="card-body">
                    <h3 class="card-title mb-3">{{ $product->name }}</h3>
                    
                    <div class="d-flex align-items-center mb-3">
                        <span class="h4 text-primary mb-0">{{ $product->price }} ₪</span>
                    </div>
                    
                    @if($product->category)
                        <div class="mb-3">
                            <strong class="text-muted">الفئة:</strong>
                            <span class="badge bg-info">{{ $product->category->name }}</span>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <strong class="text-muted">المتجر:</strong>
                        <a href="{{ route('stores.show', $product->store) }}">{{ $product->store->name }}</a>
                    </div>
                    
                    <div class="mb-3">
                        <strong class="text-muted">تاريخ الإضافة:</strong>
                        <span>{{ $product->created_at->format('Y-m-d') }}</span>
                    </div>
                    
                    @if($product->description)
                        <div class="mt-4">
                            <h5>وصف المنتج</h5>
                            <p class="card-text">{{ $product->description }}</p>
                        </div>
                    @endif
                    
                    @if(Auth::id() == $product->store->user_id)
                        <div class="d-flex mt-4">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-primary me-2">
                                <i class="fas fa-edit"></i> تعديل
                            </a>
                            
                            <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                    <i class="fas fa-trash"></i> حذف
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    @if(Auth::id() == $product->store->user_id)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">إدارة المنتج</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            تحديث المعلومات</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">تعديل بيانات المنتج</div>
                                    </div>
                                    <div class="col-auto">
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-circle btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card border-left-danger shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                            حذف المنتج</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">إزالة المنتج نهائياً</div>
                                    </div>
                                    <div class="col-auto">
                                        <form method="POST" action="{{ route('products.destroy', $product) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-circle btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection