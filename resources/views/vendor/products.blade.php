@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">إدارة منتجات المتجر</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('vendor.store') }}" class="btn btn-sm btn-outline-secondary me-2">
                <i class="fas fa-store"></i> العودة للمتجر
            </a>
            <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> إضافة منتج جديد
            </a>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">قائمة المنتجات</h6>
            <form method="GET" action="{{ route('vendor.products') }}" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="البحث عن منتج..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">بحث</button>
            </form>
        </div>
        <div class="card-body">
            @if(count($products) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>الصورة</th>
                                <th>اسم المنتج</th>
                                <th>السعر</th>
                                <th>الفئة</th>
                                <th>تاريخ الإضافة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td class="text-center">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary text-white d-inline-flex justify-content-center align-items-center" style="width: 50px; height: 50px;">
                                                <i class="fas fa-box"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->price }} ₪</td>
                                    <td>{{ $product->category->name ?? 'غير محدد' }}</td>
                                    <td>{{ $product->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info" title="عرض المنتج">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-primary" title="تعديل المنتج">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="حذف المنتج" onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
</div>
@endsection