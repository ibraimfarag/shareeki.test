@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تعديل نوع الإعلان: {{ $adType->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.ad-types.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> العودة
                        </a>
                    </div>
                </div>
                
                <form action="{{ route('admin.ad-types.update', $adType) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">اسم النوع *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $adType->name) }}" required>
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
                                           id="base_price" name="base_price" value="{{ old('base_price', $adType->base_price) }}" required>
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
                                           id="duration_days" name="duration_days" value="{{ old('duration_days', $adType->duration_days) }}" required>
                                    @error('duration_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>خصائص النوع</label><br>
                                    
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="is_paid" name="is_paid" value="1" 
                                               {{ old('is_paid', $adType->is_paid) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_paid">مدفوع</label>
                                    </div>
                                    
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="is_recurring" name="is_recurring" value="1" 
                                               {{ old('is_recurring', $adType->is_recurring) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_recurring">متكرر</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">الوصف</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $adType->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>المميزات المضافة</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="badge" name="features[badge]" value="1"
                                               {{ old('features.badge', $adType->features['badge'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="badge">
                                            <i class="fa fa-star text-warning"></i> شارة مميزة
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="pin" name="features[pin]" value="1"
                                               {{ old('features.pin', $adType->features['pin'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="pin">
                                            <i class="fa fa-thumb-tack text-info"></i> تثبيت في الأعلى
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="slider" name="features[slider]" value="1"
                                               {{ old('features.slider', $adType->features['slider'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="slider">
                                            <i class="fa fa-images text-success"></i> عرض في السلايدر
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($adType->posts_count ?? 0 > 0)
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i>
                            هذا النوع مستخدم في {{ $adType->posts_count ?? 0 }} إعلان. التعديل سيؤثر على الإعلانات الجديدة فقط.
                        </div>
                        @endif
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i> حفظ التعديلات
                        </button>
                        <a href="{{ route('admin.ad-types.index') }}" class="btn btn-secondary">
                            <i class="fa fa-times"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
