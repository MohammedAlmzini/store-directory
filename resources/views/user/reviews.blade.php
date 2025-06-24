@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">تقييماتي</h1>
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
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">جميع تقييماتي</h6>
        </div>
        <div class="card-body">
            @if(count($reviews) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>المتجر</th>
                                <th>التقييم</th>
                                <th>التعليق</th>
                                <th>التاريخ</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                                <tr>
                                    <td>
                                        <a href="{{ route('stores.show', $review->store) }}">{{ $review->store->name }}</a>
                                    </td>
                                    <td>
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                        @endfor
                                    </td>
                                    <td>{{ $review->comment ?: 'لا يوجد تعليق' }}</td>
                                    <td>{{ $review->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('user.reviews.edit', $review) }}" class="btn btn-sm btn-primary" title="تعديل التقييم">
                                                <i class="fas fa-edit"></i> تعديل
                                            </a>
                                            <form method="POST" action="{{ route('reviews.destroy', $review) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="حذف التقييم" onclick="return confirm('هل أنت متأكد من حذف هذا التقييم؟')">
                                                    <i class="fas fa-trash"></i> حذف
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
                    {{ $reviews->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <p class="mb-0">لم تقم بإضافة أي تقييمات بعد.</p>
                    <p>قم بزيارة صفحات المتاجر وإضافة تقييمات لتظهر هنا.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">إحصائيات تقييماتي</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="text-center mb-4">
                        <h3>عدد التقييمات: {{ count($reviews) }}</h3>
                        @if(count($reviews) > 0)
                            <div class="mb-2">
                                <h4>متوسط تقييماتي: {{ round($reviews->avg('rating'), 1) }}</h4>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star fa-2x {{ $i <= round($reviews->avg('rating')) ? 'text-warning' : 'text-secondary' }}"></i>
                                @endfor
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>5 نجوم</span>
                            <span>{{ $reviews->where('rating', 5)->count() }}</span>
                        </div>
                        <div class="progress mb-2" style="height: 10px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ count($reviews) > 0 ? ($reviews->where('rating', 5)->count() / count($reviews) * 100) : 0 }}%" aria-valuenow="{{ count($reviews) > 0 ? ($reviews->where('rating', 5)->count() / count($reviews) * 100) : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-1">
                            <span>4 نجوم</span>
                            <span>{{ $reviews->where('rating', 4)->count() }}</span>
                        </div>
                        <div class="progress mb-2" style="height: 10px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ count($reviews) > 0 ? ($reviews->where('rating', 4)->count() / count($reviews) * 100) : 0 }}%" aria-valuenow="{{ count($reviews) > 0 ? ($reviews->where('rating', 4)->count() / count($reviews) * 100) : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-1">
                            <span>3 نجوم</span>
                            <span>{{ $reviews->where('rating', 3)->count() }}</span>
                        </div>
                        <div class="progress mb-2" style="height: 10px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ count($reviews) > 0 ? ($reviews->where('rating', 3)->count() / count($reviews) * 100) : 0 }}%" aria-valuenow="{{ count($reviews) > 0 ? ($reviews->where('rating', 3)->count() / count($reviews) * 100) : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-1">
                            <span>2 نجوم</span>
                            <span>{{ $reviews->where('rating', 2)->count() }}</span>
                        </div>
                        <div class="progress mb-2" style="height: 10px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ count($reviews) > 0 ? ($reviews->where('rating', 2)->count() / count($reviews) * 100) : 0 }}%" aria-valuenow="{{ count($reviews) > 0 ? ($reviews->where('rating', 2)->count() / count($reviews) * 100) : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-1">
                            <span>1 نجمة</span>
                            <span>{{ $reviews->where('rating', 1)->count() }}</span>
                        </div>
                        <div class="progress mb-2" style="height: 10px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ count($reviews) > 0 ? ($reviews->where('rating', 1)->count() / count($reviews) * 100) : 0 }}%" aria-valuenow="{{ count($reviews) > 0 ? ($reviews->where('rating', 1)->count() / count($reviews) * 100) : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection