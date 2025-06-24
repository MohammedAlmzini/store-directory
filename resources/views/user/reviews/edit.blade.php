@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">تعديل التقييم</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('user.reviews') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-right"></i> العودة للتقييمات
            </a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">تعديل تقييم لمتجر {{ $review->store->name }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('user.reviews.update', $review) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label">التقييم</label>
                            <div class="rating-stars">
                                <div class="btn-group" role="group">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" class="btn-check" name="rating" id="rating{{ $i }}" value="{{ $i }}" {{ old('rating', $review->rating) == $i ? 'checked' : '' }}>
                                        <label class="btn btn-outline-warning" for="rating{{ $i }}">{{ $i }} <i class="fas fa-star"></i></label>
                                    @endfor
                                </div>
                            </div>
                            @error('rating')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="comment" class="form-label">التعليق</label>
                            <textarea class="form-control" id="comment" name="comment" rows="5">{{ old('comment', $review->comment) }}</textarea>
                            @error('comment')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                            <a href="{{ route('user.reviews') }}" class="btn btn-outline-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">معلومات المتجر</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        @if($review->store->logo)
                            <img src="{{ asset('storage/' . $review->store->logo) }}" alt="{{ $review->store->name }}" class="img-fluid rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <div class="bg-secondary text-white rounded-circle d-inline-flex justify-content-center align-items-center me-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-store fa-lg"></i>
                            </div>
                        @endif
                        
                        <div>
                            <h5 class="mb-0">{{ $review->store->name }}</h5>
                            @if($review->store->city)
                                <p class="text-muted mb-0">
                                    <i class="fas fa-map-marker-alt"></i> {{ $review->store->city }}
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('stores.show', $review->store) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i> عرض صفحة المتجر
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection