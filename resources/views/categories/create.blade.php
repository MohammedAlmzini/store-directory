@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">إضافة فئة جديدة</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('categories.store') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">اسم الفئة</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> إضافة
                    </button>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i> العودة
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection