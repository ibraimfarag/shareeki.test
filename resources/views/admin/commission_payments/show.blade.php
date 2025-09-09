@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">تفاصيل عملية العمولة</h1>
        <div class="card">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">المستخدم</dt>
                    <dd class="col-sm-9">{{ $payment->user->name ?? '-' }}</dd>

                    <dt class="col-sm-3">المبلغ الأساسي</dt>
                    <dd class="col-sm-9">{{ number_format($payment->amount, 2) }} ريال</dd>

                    <dt class="col-sm-3">العمولة</dt>
                    <dd class="col-sm-9">{{ number_format($payment->commission, 2) }} ريال</dd>

                    <dt class="col-sm-3">Payment ID</dt>
                    <dd class="col-sm-9">{{ $payment->payment_id }}</dd>

                    <dt class="col-sm-3">الحالة</dt>
                    <dd class="col-sm-9">
                        @if($payment->status == 'success')
                            <span class="badge bg-success">ناجحة</span>
                        @elseif($payment->status == 'failed')
                            <span class="badge bg-danger">فاشلة</span>
                        @elseif($payment->status == 'canceled')
                            <span class="badge bg-danger">ملغاة</span>
                        @else
                            <span class="badge bg-warning text-dark">معلقة</span>
                        @endif
                    </dd>

                    <dt class="col-sm-3">تاريخ العملية</dt>
                    <dd class="col-sm-9">{{ $payment->created_at->format('Y-m-d H:i') }}</dd>
                </dl>
                <a href="{{ route('admin.commission_payments.index') }}" class="btn btn-secondary">رجوع للسجل</a>
            </div>
        </div>
    </div>
@endsection