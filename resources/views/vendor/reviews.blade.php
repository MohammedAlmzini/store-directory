@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">تقييمات المتجر</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('vendor.store') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-store"></i> العودة للمتجر
            </a>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">جميع التقييمات</h6>
            
            <div class="btn-group" role="group">
                <a href="{{ route('vendor.reviews', ['rating' => 'all']) }}" class="btn {{ request('rating', 'all') == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">الكل</a>
                <a href="{{ route('vendor.reviews', ['rating' => 5]) }}" class="btn {{ request('rating') == 5 ? 'btn-primary' : 'btn-outline-primary' }}">5 نجوم</a>
                <a href="{{ route('vendor.reviews', ['rating' => 4]) }}" class="btn {{ request('rating') == 4 ? 'btn-primary' : 'btn-outline-primary' }}">4 نجوم</a>
                <a href="{{ route('vendor.reviews', ['rating' => 3]) }}" class="btn {{ request('rating') == 3 ? 'btn-primary' : 'btn-outline-primary' }}">3 نجوم</a>
                <a href="{{ route('vendor.reviews', ['rating' => 2]) }}" class="btn {{ request('rating') == 2 ? 'btn-primary' : 'btn-outline-primary' }}">2 نجوم</a>
                <a href="{{ route('vendor.reviews', ['rating' => 1]) }}" class="btn {{ request('rating') == 1 ? 'btn-primary' : 'btn-outline-primary' }}">1 نجمة</a>
            </div>
        </div>
        <div class="card-body">
            @if(count($reviews) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>المستخدم</th>
                                <th>التقييم</th>
                                <th>التعليق</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
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
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $reviews->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <p class="mb-0">لا توجد تقييمات لمتجرك حتى الآن.</p>
                </div>
            @endif
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">ملخص التقييمات</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h1 class="display-4">
                            {{ count($reviews) > 0 ? round($reviews->avg('rating'), 1) : '0' }}
                        </h1>
                        <div class="mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star fa-2x {{ $i <= round($reviews->avg('rating')) ? 'text-warning' : 'text-secondary' }}"></i>
                            @endfor
                        </div>
                        <p>بناءً على {{ count($reviews) }} تقييم</p>
                    </div>
                    
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
        
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">نصائح لتحسين التقييمات</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex">
                            <div class="me-3 text-primary">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                            <div>
                                <h6>استجب للتقييمات</h6>
                                <p class="text-muted mb-0">تفاعل مع العملاء وأظهر اهتمامك بآرائهم وملاحظاتهم.</p>
                            </div>
                        </li>
                        <li class="list-group-item d-flex">
                            <div class="me-3 text-primary">
                                <i class="fas fa-box-open fa-2x"></i>
                            </div>
                            <div>
                                <h6>حدث معلومات المنتجات</h6>
                                <p class="text-muted mb-0">تأكد من أن جميع المنتجات لها وصف دقيق وصور واضحة.</p>
                            </div>
                        </li>
                        <li class="list-group-item d-flex">
                            <div class="me-3 text-primary">
                                <i class="fas fa-heart fa-2x"></i>
                            </div>
                            <div>
                                <h6>قدم خدمة عملاء متميزة</h6>
                                <p class="text-muted mb-0">الاهتمام بتجربة العملاء يؤدي إلى تقييمات إيجابية.</p>
                            </div>
                        </li>
                        <li class="list-group-item d-flex">
                            <div class="me-3 text-primary">
                                <i class="fas fa-sync-alt fa-2x"></i>
                            </div>
                            <div>
                                <h6>حدث معلومات متجرك</h6>
                                <p class="text-muted mb-0">تأكد من تحديث معلومات الاتصال ووصف المتجر بانتظام.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection