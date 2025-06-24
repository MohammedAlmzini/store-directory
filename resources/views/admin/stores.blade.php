@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">إدارة المتاجر</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">قائمة المتاجر</h6>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-8">
                    <form method="GET" action="{{ route('admin.stores') }}" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="البحث عن متجر..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">بحث</button>
                    </form>
                </div>
                <div class="col-md-4">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('admin.stores', ['status' => 'all']) }}" class="btn {{ request('status', 'all') == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">الكل</a>
                        <a href="{{ route('admin.stores', ['status' => 'pending']) }}" class="btn {{ request('status') == 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">قيد الانتظار</a>
                        <a href="{{ route('admin.stores', ['status' => 'approved']) }}" class="btn {{ request('status') == 'approved' ? 'btn-primary' : 'btn-outline-primary' }}">مفعل</a>
                        <a href="{{ route('admin.stores', ['status' => 'rejected']) }}" class="btn {{ request('status') == 'rejected' ? 'btn-primary' : 'btn-outline-primary' }}">مرفوض</a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>الشعار</th>
                            <th>اسم المتجر</th>
                            <th>صاحب المتجر</th>
                            <th>الفئة</th>
                            <th>المدينة</th>
                            <th>الحالة</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stores as $store)
                            <tr>
                                <td class="text-center">
                                    @if($store->logo)
                                        <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="img-fluid rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary text-white rounded-circle d-inline-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-store"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $store->name }}</td>
                                <td>{{ $store->user->name }}</td>
                                <td>{{ $store->category->name ?? 'غير محدد' }}</td>
                                <td>{{ $store->city ?: 'غير محدد' }}</td>
                                <td>
                                    @if($store->status === 'pending')
                                        <span class="badge bg-warning text-dark">قيد الانتظار</span>
                                    @elseif($store->status === 'approved')
                                        <span class="badge bg-success">مفعل</span>
                                    @else
                                        <span class="badge bg-danger">مرفوض</span>
                                    @endif
                                </td>
                                <td>{{ $store->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('stores.show', $store) }}" class="btn btn-sm btn-primary" title="عرض المتجر">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($store->status === 'pending')
                                            <form method="POST" action="{{ route('stores.update-status', $store) }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-sm btn-success" title="قبول المتجر">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            
                                            <form method="POST" action="{{ route('stores.update-status', $store) }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-sm btn-danger" title="رفض المتجر">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @elseif($store->status === 'approved')
                                            <form method="POST" action="{{ route('stores.update-status', $store) }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-sm btn-danger" title="إلغاء تفعيل المتجر">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('stores.update-status', $store) }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-sm btn-success" title="تفعيل المتجر">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form method="POST" action="{{ route('stores.destroy', $store) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="حذف المتجر" onclick="return confirm('هل أنت متأكد من حذف هذا المتجر؟ سيتم حذف جميع المنتجات والتقييمات المرتبطة به.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">لا توجد متاجر</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $stores->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection