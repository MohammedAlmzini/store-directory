@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">تعديل المتجر</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('stores.update', $store) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">اسم المتجر</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $store->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="category_id" class="form-label">الفئة</label>
                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                        <option value="">اختر فئة</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $store->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="logo" class="form-label">شعار المتجر</label>
                    @if($store->logo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="img-thumbnail" style="max-height: 100px;">
                        </div>
                    @endif
                    <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo">
                    @error('logo')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">وصف المتجر</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $store->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="contact_info" class="form-label">معلومات التواصل</label>
                    <input type="text" class="form-control @error('contact_info') is-invalid @enderror" id="contact_info" name="contact_info" value="{{ old('contact_info', $store->contact_info) }}">
                    @error('contact_info')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="city" class="form-label">المدينة</label>
                    <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $store->city) }}">
                    @error('city')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> تحديث المتجر
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i> العودة
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection