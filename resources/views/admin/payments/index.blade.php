@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">سجل المدفوعات</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">جميع المعاملات</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>المستخدم</th>
                                <th>المبلغ</th>
                                <th>نوع المعاملة</th>
                                <th>الحالة</th>
                                <th>تاريخ العملية</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>{{ $payment->id }}</td>
                                    <td>{{ $payment->user->name }}</td>
                                    <td>{{ number_format($payment->amount, 2) }} ريال</td>
                                    <td>
                                        @if($payment->payable_type === 'App\Models\Post')
                                            تمييز إعلان
                                        @else
                                            {{ $payment->payable_type }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->status)
                                            <span
                                                class="badge bg-{{ $payment->status == 'paid' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }}">
                                                @if($payment->status == 'paid')
                                                    تم الدفع
                                                @elseif($payment->status == 'pending')
                                                    معلق
                                                @elseif($payment->status == 'failed')
                                                    فشل
                                                @else
                                                    {{ $payment->status }}
                                                @endif
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">غير معروف</span>
                                        @endif
                                    </td>
                                    <td>{{ $payment->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> عرض
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection