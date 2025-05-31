@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">لوحة تحكم صاحب المتجر</h1>
    </div>

    @if(!$store)
        <div class="alert alert-info">
            <h4>لم تقم بإنشاء متجر بعد!</h4>
            <p>يمكنك إنشاء متجرك الخاص من خلال النقر على الزر أدناه.</p>
            <a href="{{ route('stores.create') }}" class="btn btn-primary">إنشاء متجر جديد</a>
        </div>
    @else
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-right-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    عدد المنتجات</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($products) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-right-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    عدد التقييمات</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($reviews) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-star fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-right-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    متوسط التقييم</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ count($reviews) > 0 ? round($reviews->avg('rating'), 1) : 'لا يوجد' }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-star-half-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-right-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    حالة المتجر</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    @if($store->status === 'pending')
                                        <span class="text-warning">قيد الانتظار</span>
                                    @elseif($store->status === 'approved')
                                        <span class="text-success">مفعل</span>
                                    @else
                                        <span class="text-danger">مرفوض</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">معلومات المتجر</h6>
                        <a href="{{ route('stores.edit', $store) }}" class="btn btn-sm btn-primary">تعديل</a>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            @if($store->logo)
                                <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="img-fluid rounded-circle" style="width: 100px; height: 100px;">
                            @else
                                <div class="bg-secondary text-white rounded-circle d-inline-flex justify-content-center align-items-center" style="width: 100px; height: 100px;">
                                    <i class="fas fa-store fa-3x"></i>
                                </div>
                            @endif
                        </div>
                        
                        <h5 class="text-center mb-3">{{ $store->name }}</h5>
                        
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>الوصف:</span>
                                <span>{{ $store->description ?: 'لا يوجد' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>معلومات الاتصال:</span>
                                <span>{{ $store->contact_info ?: 'لا يوجد' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>المدينة:</span>
                                <span>{{ $store->city ?: 'لا يوجد' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>تاريخ الإنشاء:</span>
                                <span>{{ $store->created_at->format('Y-m-d') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">أحدث المنتجات</h6>
                        <a href="{{ route('products.create') }}" class="btn btn-sm btn-success">إضافة منتج</a>
                    </div>
                    <div class="card-body">
                        @if(count($products) > 0)
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>الاسم</th>
                                            <th>السعر</th>
                                            <th>الفئة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products->take(5) as $product)
                                            <tr>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->price }}</td>
                                                <td>{{ $product->category->name }}</td>
                                                <td>
                                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-primary">تعديل</a>
                                                    <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if(count($products) > 5)
                                <div class="text-center mt-3">
                                    <a href="{{ route('products.index') }}" class="btn btn-link">عرض جميع المنتجات</a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-3">
                                <p>لا يوجد منتجات حتى الآن.</p>
                                <a href="{{ route('products.create') }}" class="btn btn-primary">إضافة منتج جديد</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">أحدث التقييمات</h6>
                    </div>
                    <div class="card-body">
                        @if(count($reviews) > 0)
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>المستخدم</th>
                                            <th>التقييم</th>
                                            <th>التعليق</th>
                                            <th>التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reviews->take(5) as $review)
                                            <tr>
                                                <td>{{ $review->user->name }}</td>
                                                <td>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                                    @endfor
                                                </td>
                                                <td>{{ $review->comment ?: 'لا يوجد تعليق' }}</td>
                                                <td>{{ $review->created_at->format('Y-m-d') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <p>لا يوجد تقييمات حتى الآن.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection