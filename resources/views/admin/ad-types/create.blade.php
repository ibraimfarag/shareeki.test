{{-- resources/views/admin/ad-types/create.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إضافة نوع إعلان جديد</h3>
                </div>
                
                <form action="{{ route('admin.ad-types.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">اسم النوع *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="base_price">السعر الأساسي *</label>
                                    <input type="number" step="0.01" min="0" 
                                           class="form-control @error('base_price') is-invalid @enderror"
                                           id="base_price" name="base_price" value="{{ old('base_price', 0) }}" required>
                                    @error('base_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="duration_days">مدة العرض (أيام) *</label>
                                    <input type="number" min="1" max="365"
                                           class="form-control @error('duration_days') is-invalid @enderror"
                                           id="duration_days" name="duration_days" value="{{ old('duration_days', 30) }}" required>
                                    @error('duration_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>خصائص النوع</label><br>
                                    
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="is_paid" name="is_paid" value="1" {{ old('is_paid') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_paid">مدفوع</label>
                                    </div>
                                    
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="is_recurring" name="is_recurring" value="1" {{ old('is_recurring') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_recurring">متكرر</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">الوصف</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>المميزات المضافة</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="badge" name="features[badge]" value="1">
                                        <label class="form-check-label" for="badge">
                                            <i class="fa fa-star text-warning"></i> شارة مميزة
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="pin" name="features[pin]" value="1">
                                        <label class="form-check-label" for="pin">
                                            <i class="fa fa-thumb-tack text-info"></i> تثبيت في الأعلى
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="slider" name="features[slider]" value="1">
                                        <label class="form-check-label" for="slider">
                                            <i class="fa fa-images text-success"></i> عرض في السلايدر
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i> حفظ
                        </button>
                        <a href="{{ route('admin.ad-types.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
