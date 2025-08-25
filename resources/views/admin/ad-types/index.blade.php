{{-- resources/views/admin/ad-types/index.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">أنواع الإعلانات</h3>
                    <a href="{{ route('admin.ad-types.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> إضافة نوع جديد
                    </a>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>الاسم</th>
                                    <th>السعر الأساسي</th>
                                    <th>المدة (أيام)</th>
                                    <th>نوع الدفع</th>
                                    <th>المميزات</th>
                                    <th>عدد الإعلانات</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($adTypes as $type)
                                <tr>
                                    <td>
                                        <strong>{{ $type->name }}</strong>
                                        @if($type->description)
                                            <br><small class="text-muted">{{ $type->description }}</small>
                                        @endif
                                    </td>
                                    <td>{{ number_format($type->base_price, 2) }} ريال</td>
                                    <td>{{ $type->duration_days }} يوم</td>
                                    <td>
                                        @if($type->is_paid)
                                            <span class=" badge-success">مدفوع</span>
                                        @else
                                            <span class=" badge-secondary">مجاني</span>
                                        @endif
                                        
                                        @if($type->is_recurring)
                                            <span class=" badge-info">متكرر</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($type->features['badge'] ?? false)
                                            <span class=" badge-primary">شارة</span>
                                        @endif
                                        @if($type->features['pin'] ?? false)
                                            <span class=" badge-warning">تثبيت</span>
                                        @endif
                                        @if($type->features['slider'] ?? false)
                                            <span class=" badge-info">سلايدر</span>
                                        @endif
                                    </td>
                                    <td>{{ $type->posts_count }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.ad-types.show', $type) }}" 
                                               class="btn btn-sm btn-info">عرض</a>
                                            <a href="{{ route('admin.ad-types.edit', $type) }}" 
                                               class="btn btn-sm btn-warning">تعديل</a>
                                            
                                            @if($type->posts_count == 0)
                                            <form action="{{ route('admin.ad-types.destroy', $type) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">حذف</button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">لا توجد أنواع إعلانات</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
