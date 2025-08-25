{{-- resources/views/admin/pricing-rules/index.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>قواعد التسعير</h3>
                    <a href="{{ route('admin.pricing-rules.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> إضافة قاعدة جديدة
                    </a>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>اسم القاعدة</th>
                                    <th>النوع</th>
                                    <th>الفئة</th>
                                    <th>المدة</th>
                                    <th>المضاعف</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rules as $rule)
                                <tr>
                                    <td>{{ $rule->rule_name }}</td>
                                    <td>{{ $rule->adType->name ?? 'جميع الأنواع' }}</td>
                                    <td>{{ $rule->category->name ?? 'جميع الفئات' }}</td>
                                    <td>
                                        {{ $rule->min_duration }}
                                        @if($rule->max_duration)
                                            - {{ $rule->max_duration }}
                                        @else
                                            +
                                        @endif
                                        {{ $rule->duration_unit == 'day' ? 'يوم' : ($rule->duration_unit == 'week' ? 'أسبوع' : ($rule->duration_unit == 'month' ? 'شهر' : 'سنة')) }}
                                    </td>
                                    <td>×{{ $rule->multiplier }}</td>
                                    <td>
                                        @if($rule->active)
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-secondary">معطل</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.pricing-rules.edit', $rule) }}" class="btn btn-sm btn-warning">تعديل</a>
                                        <form action="{{ route('admin.pricing-rules.destroy', $rule) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('متأكد من الحذف؟')">حذف</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
