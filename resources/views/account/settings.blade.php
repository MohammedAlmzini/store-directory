@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">إعدادات الحساب</h1>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">المعلومات الشخصية</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('account.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">الاسم</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">نوع الحساب</label>
                            <input type="text" class="form-control" value="{{ $user->role === 'admin' ? 'مشرف' : ($user->role === 'vendor' ? 'صاحب متجر' : 'مستخدم') }}" readonly>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">تغيير كلمة المرور</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('account.password') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">كلمة المرور الحالية</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور الجديدة</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key"></i> تغيير كلمة المرور
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            @if($user->role === 'vendor')
                <div class="card shadow mt-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">معلومات المتجر</h6>
                    </div>
                    <div class="card-body">
                        @if($user->store)
                            <div class="d-flex align-items-center mb-3">
                                @if($user->store->logo)
                                    <img src="{{ asset('storage/' . $user->store->logo) }}" alt="{{ $user->store->name }}" class="img-fluid rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary text-white rounded-circle d-inline-flex justify-content-center align-items-center me-3" style="width: 50px; height: 50px;">
                                        <i class="fas fa-store"></i>
                                    </div>
                                @endif
                                <h5 class="mb-0">{{ $user->store->name }}</h5>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('stores.edit', $user->store) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit"></i> تعديل معلومات المتجر
                                </a>
                                <a href="{{ route('stores.show', $user->store) }}" class="btn btn-outline-info">
                                    <i class="fas fa-eye"></i> عرض المتجر
                                </a>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <p>ليس لديك متجر حتى الآن.</p>
                                <a href="{{ route('stores.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> إنشاء متجر جديد
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection