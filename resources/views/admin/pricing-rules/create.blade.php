{{-- resources/views/admin/pricing-rules/create.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3>إضافة قاعدة تسعير جديدة</h3>
        </div>
        
        <form action="{{ route('admin.pricing-rules.store') }}" method="POST">
            @csrf
            <div class="card-body">
                
                {{-- اسم القاعدة --}}
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="rule_name" class="form-label">اسم القاعدة *</label>
                        <input type="text" class="form-control" id="rule_name" name="rule_name" 
                               placeholder="مثل: خصم الأسبوع الأول" required>
                        <small class="text-muted">اسم وصفي لتمييز القاعدة</small>
                    </div>
                </div>

                {{-- نوع الإعلان والفئة --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="ad_type_id" class="form-label">نوع الإعلان</label>
                        <select class="form-control" id="ad_type_id" name="ad_type_id">
                            <option value="">جميع الأنواع</option>
                            @foreach($adTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">اختر نوع محدد أو اتركه فارغ للكل</small>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">الفئة</label>
                        <select class="form-control" id="category_id" name="category_id">
                            <option value="">جميع الفئات</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">اختر فئة محددة أو اتركه فارغ للكل</small>
                    </div>
                </div>

                {{-- وحدة الزمن --}}
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="duration_unit" class="form-label">وحدة الزمن *</label>
                        <select class="form-control" id="duration_unit" name="duration_unit" required>
                            <option value="day">يوم</option>
                            <option value="week">أسبوع</option>
                            <option value="month">شهر</option>
                            <option value="year">سنة</option>
                        </select>
                    </div>
                </div>

                {{-- نطاق المدة --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="min_duration" class="form-label">الحد الأدنى للمدة *</label>
                        <input type="number" class="form-control" id="min_duration" name="min_duration" 
                               min="1" value="1" required>
                        <small class="text-muted">أقل عدد من الوحدات</small>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="max_duration" class="form-label">الحد الأقصى للمدة</label>
                        <input type="number" class="form-control" id="max_duration" name="max_duration" min="1">
                        <small class="text-muted">اتركه فارغ للدلالة على "أو أكثر"</small>
                    </div>
                </div>

                {{-- المضاعف --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="multiplier" class="form-label">مضاعف السعر *</label>
                        <input type="number" step="0.01" min="0.1" max="10" 
                               class="form-control" id="multiplier" name="multiplier" 
                               value="1.00" required>
                        <small class="text-muted">
                            1.00 = عادي | أقل من 1 = خصم | أكبر من 1 = زيادة
                        </small>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="priority" class="form-label">الأولوية</label>
                        <input type="number" class="form-control" id="priority" name="priority" 
                               value="0" min="0" max="100">
                        <small class="text-muted">رقم أعلى = أولوية أكبر</small>
                    </div>
                </div>

                {{-- حالة التفعيل --}}
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="active" name="active" value="1" checked>
                            <label class="form-check-label" for="active">تفعيل القاعدة</label>
                        </div>
                    </div>
                </div>

                {{-- أمثلة توضيحية --}}
                <div class="alert alert-info">
                    <h6><i class="fa fa-lightbulb"></i> أمثلة:</h6>
                    <ul class="mb-0">
                        <li><strong>خصم أسبوعي:</strong> الحد الأدنى: 7، الأقصى: 30، الوحدة: يوم، المضاعف: 0.85 (خصم 15%)</li>
                        <li><strong>زيادة قصيرة المدى:</strong> الحد الأدنى: 1، الأقصى: 3، الوحدة: يوم، المضاعف: 1.20 (زيادة 20%)</li>
                        <li><strong>خصم طويل المدى:</strong> الحد الأدنى: 3، الأقصى: فارغ، الوحدة: شهر، المضاعف: 0.70 (خصم 30%)</li>
                    </ul>
                </div>
            </div>
            
            <div class="card-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> حفظ القاعدة
                </button>
                <a href="{{ route('admin.pricing-rules.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
