@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">لوحة تحكم المستخدم</h1>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">المتاجر المفضلة</h6>
                </div>
                <div class="card-body">
                    @if(count($favorites) > 0)
                        <div class="row">
                            @foreach($favorites as $favorite)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="text-center mt-3">
                                            @if($favorite->store->logo)
                                                <img src="{{ asset('storage/' . $favorite->store->logo) }}" alt="{{ $favorite->store->name }}" class="img-fluid rounded-circle" style="width: 80px; height: 80px;">
                                            @else
                                                <div class="bg-secondary text-white rounded-circle d-inline-flex justify-content-center align-items-center" style="width: 80px; height: 80px;">
                                                    <i class="fas fa-store fa-2x"></i>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="card-body text-center">
                                            <h5 class="card-title">{{ $favorite->store->name }}</h5>
                                            <p class="card-text small text-muted">{{ $favorite->store->city ?: 'لا توجد مدينة' }}</p>
                                            
                                            <div class="d-flex justify-content-center mt-3">
                                                <a href="{{ route('stores.show', $favorite->store) }}" class="btn btn-sm btn-primary me-2">عرض</a>
                                                <form method="POST" action="{{ route('favorites.remove', $favorite->store) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">إزالة</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p>لا توجد متاجر مفضلة حتى الآن.</p>
                            <a href="{{ route('stores.index') }}" class="btn btn-primary">تصفح المتاجر</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">تقييماتي</h6>
                </div>
                <div class="card-body">
                    @if(count($reviews) > 0)
                        <div class="list-group">
                            @foreach($reviews as $review)
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $review->store->name }}</h5>
                                        <small>{{ $review->created_at->format('Y-m-d') }}</small>
                                    </div>
                                    <div class="mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                        @endfor
                                    </div>
                                    <p class="mb-1">{{ $review->comment ?: 'لا يوجد تعليق' }}</p>
                                    <div class="d-flex mt-2">
                                        <a href="{{ route('stores.show', $review->store) }}" class="btn btn-sm btn-primary me-2">عرض المتجر</a>
                                        <form method="POST" action="{{ route('reviews.destroy', $review) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد من حذف هذا التقييم؟')">حذف</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p>لم تقم بتقييم أي متجر حتى الآن.</p>
                            <a href="{{ route('stores.index') }}" class="btn btn-primary">تصفح المتاجر</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection