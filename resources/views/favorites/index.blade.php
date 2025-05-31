@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">المتاجر المفضلة</h1>
    </div>
    
    <div class="row">
        @if(count($favorites) > 0)
            @foreach($favorites as $favorite)
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="text-center mt-3">
                            @if($favorite->store->logo)
                                <img src="{{ asset('storage/' . $favorite->store->logo) }}" alt="{{ $favorite->store->name }}" class="img-fluid rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="bg-secondary text-white rounded-circle d-inline-flex justify-content-center align-items-center" style="width: 100px; height: 100px;">
                                    <i class="fas fa-store fa-3x"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $favorite->store->name }}</h5>
                            
                            @if($favorite->store->city)
                                <p class="text-muted">
                                    <i class="fas fa-map-marker-alt"></i> {{ $favorite->store->city }}
                                </p>
                            @endif
                            
                            <div class="mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $favorite->store->average_rating ? 'text-warning' : 'text-secondary' }}"></i>
                                @endfor
                                <span class="text-muted">({{ $favorite->store->reviews->count() }})</span>
                            </div>
                            
                            <p class="card-text small">
                                {{ Str::limit($favorite->store->description, 100) }}
                            </p>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('stores.show', $favorite->store) }}" class="btn btn-primary">
                                    <i class="fas fa-eye"></i> عرض المتجر
                                </a>
                                
                                <form method="POST" action="{{ route('favorites.remove', $favorite->store) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="fas fa-heart"></i> إزالة من المفضلة
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <div class="d-flex justify-content-center mt-4">
                {{ $favorites->links() }}
            </div>
        @else
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <h4 class="alert-heading">لا توجد متاجر مفضلة!</h4>
                    <p>لم تقم بإضافة أي متجر إلى المفضلة حتى الآن.</p>
                    <a href="{{ route('stores.index') }}" class="btn btn-primary mt-2">
                        <i class="fas fa-store"></i> استكشف المتاجر
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection