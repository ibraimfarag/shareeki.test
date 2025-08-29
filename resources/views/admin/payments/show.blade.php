@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>تفاصيل عملية الدفع</h4>
                    <div>
                        <a href="{{ route('admin.payments.download', $payment->id) }}" class="btn btn-primary me-2">
                            <i class="fas fa-download"></i> تحميل الفاتورة
                        </a>
                        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i> عودة للقائمة
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>رقم العملية</th>
                                    <td>{{ $payment->transaction_id }}</td>
                                </tr>
                                <tr>
                                    <th>المستخدم</th>
                                    <td>{{ $payment->user->name ?? 'غير متوفر' }}</td>
                                </tr>
                                <tr>
                                    <th>البريد الإلكتروني</th>
                                    <td>{{ $payment->user->email ?? 'غير متوفر' }}</td>
                                </tr>
                                <tr>
                                    <th>المبلغ</th>
                                    <td>{{ number_format($payment->amount, 2) }} ريال</td>
                                </tr>
                                <tr>
                                    <th>نوع الخدمة</th>
                                    <td>
                                        @if($payment->payable_type === 'App\Models\Post')
                                            إعلان مميز
                                            @if($payment->payable)
                                                <a href="{{ asset('admin/posts/' . $payment->payable->id) }}" class="btn btn-link">
                                                    عرض الإعلان
                                                </a>
                                            @endif
                                        @else
                                            {{ $payment->payable_type }}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>الحالة</th>
                                    <td>
                                        @if($payment->status === 'completed')
                                            <span class="badge bg-success">مكتمل</span>
                                        @elseif($payment->status === 'pending')
                                            <span class="badge bg-warning">قيد المعالجة</span>
                                        @elseif($payment->status === 'failed')
                                            <span class="badge bg-danger">فشل</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>تاريخ العملية</th>
                                    <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>طريقة الدفع</th>
                                    <td>
                                        @if($payment->gateway === 'rajhi')
                                            بنك الراجحي
                                            @if(isset($payment->payment_data['tranRef']))
                                                <br>
                                                <small class="text-muted">
                                                    الرقم المرجعي: {{ $payment->payment_data['tranRef'] }}
                                                    <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('{{ $payment->payment_data['tranRef'] }}')">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </small>
                                            @endif
                                        @else
                                            {{ $payment->gateway ?? 'غير متوفر' }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>حالة الدفع</th>
                                    <td>
                                        @if($payment->status === 'completed')
                                            <span class="badge bg-success">تم القبول</span>
                                        @elseif($payment->status === 'pending')
                                            <span class="badge bg-warning">قيد المعالجة</span>
                                        @elseif($payment->status === 'failed')
                                            <span class="badge bg-danger">مرفوض</span>
                                            @if($payment->error_message)
                                                <br>
                                                <small class="text-danger">{{ $payment->error_message }}</small>
                                            @endif
                                        @elseif($payment->status === 'cancelled')
                                            <span class="badge bg-secondary">ملغي</span>
                                        @else
                                            <span class="badge bg-info">{{ $payment->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>بيانات الدفع</th>
                                    <td>
                                        @if($payment->payment_data)
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    @foreach((array)$payment->payment_data as $key => $value)
                                                        <tr>
                                                            <th>{{ $key }}</th>
                                                            <td>{{ is_array($value) ? json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $value }}</td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </div>
                                        @else
                                            <span class="text-muted">لا توجد بيانات إضافية</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($payment->notes)
                                <tr>
                                    <th>ملاحظات</th>
                                    <td>{{ $payment->notes }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($payment->logs && count($payment->logs) > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>سجل العملية</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>التاريخ</th>
                                        <th>الحالة</th>
                                        <th>الوصف</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payment->logs as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>{{ $log->status }}</td>
                                        <td>{{ $log->description }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
