@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
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
                    
                    @if($store->status != 'approved')
                        <div class="alert alert-warning">
                            هذا المتجر {{ $store->status == 'pending' ? 'قيد المراجعة' : 'مرفوض' }}
                        </div>
                    @endif
                    
                    <div class="d-flex justify-content-center mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star fa-lg {{ $i <= $store->average_rating ? 'text-warning' : 'text-secondary' }}"></i>
                        @endfor
                        <span class="ms-2">({{ $store->reviews->count() }} تقييم)</span>
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
                    
                    @auth
                        <div class="d-grid gap-2 mt-3">
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
                        </div>
                    @endauth
                </div>
            </div>
            
            @auth
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">أضف تقييمًا</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('reviews.store') }}">
                            @csrf
                            <input type="hidden" name="store_id" value="{{ $store->id }}">
                            
                            <div class="mb-3">
                                <label class="form-label">التقييم</label>
                                <div class="rating-stars">
                                    <div class="btn-group" role="group">
                                        @for($i = 5; $i >= 1; $i--)
                                            <input type="radio" class="btn-check" name="rating" id="rating{{ $i }}" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                                            <label class="btn btn-outline-warning" for="rating{{ $i }}">{{ $i }} <i class="fas fa-star"></i></label>
                                        @endfor
                                    </div>
                                </div>
                                @error('rating')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="comment" class="form-label">التعليق</label>
                                <textarea class="form-control" id="comment" name="comment" rows="3">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">إرسال التقييم</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endauth
            
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">التقييمات ({{ $reviews->count() }})</h5>
                </div>
                <div class="card-body">
                    @if(count($reviews) > 0)
                        @foreach($reviews as $review)
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
                                
                                @auth
                                    @if(Auth::id() == $review->user_id || Auth::user()->role == 'admin')
                                        <div class="mt-2 text-end">
                                            <form method="POST" action="{{ route('reviews.destroy', $review) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد من حذف هذا التقييم؟')">
                                                    <i class="fas fa-trash"></i> حذف
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
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
                    @if(Auth::check() && Auth::id() == $store->user_id)
                        <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> إضافة منتج
                        </a>
                    @endif
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
                                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> عرض التفاصيل
                                                </a>
                                                
                                                @if(Auth::check() && Auth::id() == $store->user_id)
                                                    <div class="btn-group mt-2" role="group">
                                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-secondary">
                                                            <i class="fas fa-edit"></i> تعديل
                                                        </a>
                                                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                                                <i class="fas fa-trash"></i> حذف
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
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
                            <p class="mb-0">لا توجد منتجات متاحة لهذا المتجر حالياً.</p>
                            
                            @if(Auth::check() && Auth::id() == $store->user_id)
                                <a href="{{ route('products.create') }}" class="btn btn-primary mt-3">
                                    <i class="fas fa-plus"></i> إضافة منتج جديد
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection