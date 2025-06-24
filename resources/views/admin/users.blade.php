@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">إدارة المستخدمين</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">قائمة المستخدمين</h6>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('admin.users') }}" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="البحث عن مستخدم..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">بحث</button>
                    </form>
                </div>
                <div class="col-md-3">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('admin.users', ['role' => 'all']) }}" class="btn {{ request('role', 'all') == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">الكل</a>
                        <a href="{{ route('admin.users', ['role' => 'admin']) }}" class="btn {{ request('role') == 'admin' ? 'btn-primary' : 'btn-outline-primary' }}">مشرفين</a>
                        <a href="{{ route('admin.users', ['role' => 'vendor']) }}" class="btn {{ request('role') == 'vendor' ? 'btn-primary' : 'btn-outline-primary' }}">تجار</a>
                        <a href="{{ route('admin.users', ['role' => 'user']) }}" class="btn {{ request('role') == 'user' ? 'btn-primary' : 'btn-outline-primary' }}">مستخدمين</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('admin.users') }}" class="btn {{ !request()->has('banned') ? 'btn-primary' : 'btn-outline-primary' }}">الكل</a>
                        <a href="{{ route('admin.users', ['banned' => 1]) }}" class="btn {{ request('banned') == '1' ? 'btn-primary' : 'btn-outline-primary' }}">المحظورين</a>
                        <a href="{{ route('admin.users', ['banned' => 0]) }}" class="btn {{ request('banned') == '0' ? 'btn-primary' : 'btn-outline-primary' }}">النشطين</a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>الدور</th>
                            <th>الحالة</th>
                            <th>تاريخ التسجيل</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge bg-danger">مشرف</span>
                                    @elseif($user->role === 'vendor')
                                        <span class="badge bg-primary">صاحب متجر</span>
                                    @else
                                        <span class="badge bg-secondary">مستخدم</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->is_banned)
                                        <span class="badge bg-danger">محظور</span>
                                    @else
                                        <span class="badge bg-success">نشط</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="btn-group">
                                        @if($user->role === 'vendor' && $user->store)
                                            <a href="{{ route('stores.show', $user->store) }}" class="btn btn-sm btn-info" title="عرض المتجر">
                                                <i class="fas fa-store"></i>
                                            </a>
                                        @endif
                                        
                                        @if($user->role !== 'admin' || auth()->id() === $user->id)
                                            @if($user->is_banned)
                                                <form method="POST" action="{{ route('admin.users.unban', $user) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="إلغاء الحظر">
                                                        <i class="fas fa-unlock"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('admin.users.ban', $user) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning" title="حظر المستخدم" onclick="return confirm('هل أنت متأكد من حظر هذا المستخدم؟')">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                        
                                        @if($user->id !== auth()->id() && $user->role !== 'admin')
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="حذف المستخدم" onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟ سيتم حذف جميع بياناته ومتجره ومنتجاته.')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">لا يوجد مستخدمين</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection