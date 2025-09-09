@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">سجل مدفوعات العمولة</h1>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>المستخدم</th>
                                <th>المبلغ الأساسي</th>
                                <th>العمولة</th>
                                <th>Payment ID</th>
                                <th>الحالة</th>
                                <th>التاريخ</th>
                                <th>تفاصيل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>{{ $payment->id }}</td>
                                    <td>
                                        @if($payment->user)
                                            <a href="{{ route('users.show', $payment->user->id) }}" target="_blank">
                                                {{ $payment->user->name }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ number_format($payment->amount, 2) }} ريال</td>
                                    <td>{{ number_format($payment->commission, 2) }} ريال</td>
                                    <td>{{ $payment->payment_id }}</td>
                                    <td>
                                        @if($payment->status == 'success')
                                            <span class="badge bg-success">ناجحة</span>
                                        @elseif($payment->status == 'canceled')
                                        <span class="badge bg-danger">ملغاة</span>
                                        @elseif($payment->status == 'failed')
                                            <span class="badge bg-danger">فاشلة</span>
                                        @else
                                            <span class="badge bg-warning text-dark">معلقة</span>
                                        @endif
                                    </td>
                                    <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                    <td><a href="{{ route('admin.commission_payments.show', $payment->id) }}"
                                            class="btn btn-sm btn-info">عرض</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-3">
            {{ $payments->links() }}
        </div>
    </div>
@endsection