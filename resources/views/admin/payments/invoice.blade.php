<!DOCTYPE html>
<html dir="rtl" lang="ar">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>فاتورة رقم {{ $payment->transaction_id }}</title>
    <style>
        body {
            font-family: xbriyaz;
            line-height: 1.6;
            color: #333;
            padding: 20px;
            direction: rtl;
            text-align: right;
        }

        * {
            font-family: xbriyaz;
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }

        .invoice-header img {
            max-width: 200px;
            margin-bottom: 10px;
        }

        .invoice-details {
            margin-bottom: 30px;
        }

        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-details th,
        .invoice-details td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .invoice-details th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: right;
            width: 30%;
        }

        .amount {
            font-size: 20px;
            font-weight: bold;
            color: #28a745;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }

        .status-completed {
            color: #28a745;
            font-weight: bold;
        }

        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }

        .status-failed {
            color: #dc3545;
            font-weight: bold;
        }

        .barcode {
            text-align: center;
            margin: 20px 0;
        }

        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="invoice-header">
        <img src="{{ $settings->header_logo }}" alt="شعار الموقع">
        <h1>فاتورة دفع</h1>
        <p>رقم العملية: {{ $payment->transaction_id }}</p>
    </div>

    <div class="invoice-details">
        <table>
            <tr>
                <th>تاريخ العملية</th>
                <td>{{ $payment->created_at ? $payment->created_at->format('Y-m-d H:i') : 'غير متوفر' }}</td>
            </tr>
            <tr>
                <th>اسم المستخدم</th>
                <td>{{ $payment->user->name ?? 'غير متوفر' }}</td>
            </tr>
            <tr>
                <th>البريد الإلكتروني</th>
                <td>{{ $payment->user->email ?? 'غير متوفر' }}</td>
            </tr>
            <tr>
                <th>نوع الخدمة</th>
                <td>
                    @if($payment->payable_type === 'App\Models\Post')
                        إعلان مميز
                        @if($payment->payable)
                            - {{ $payment->payable->title }}
                        @endif
                    @else
                        {{ $payment->payable_type }}
                    @endif
                </td>
            </tr>
            <tr>
                <th>طريقة الدفع</th>
                <td>
                    @if($payment->gateway === 'rajhi')
                        بنك الراجحي
                        @if(isset($payment->payment_data['tranRef']))
                            <br>
                            <small>الرقم المرجعي: {{ $payment->payment_data['tranRef'] }}</small>
                        @endif
                    @else
                        {{ $payment->gateway ?? 'غير متوفر' }}
                    @endif
                </td>
            </tr>
            <tr>
                <th>حالة الدفع</th>
                <td class="status-{{ $payment->status }}">
                    @if($payment->status === 'completed')
                        تم القبول
                    @elseif($payment->status === 'pending')
                        قيد المعالجة
                    @elseif($payment->status === 'failed')
                        مرفوض
                    @else
                        {{ $payment->status }}
                    @endif
                </td>
            </tr>
            <tr>
                <th>المبلغ</th>
                <td class="amount">{{ number_format($payment->amount, 2) }} ريال</td>
            </tr>
            <tr>
                <th>تفاصيل المبلغ</th>
                <td>
                    <ul style="list-style: none; padding: 0;">
                        <li>قيمة الإعلان: {{ number_format($payment->amount, 2) }} ريال</li>
                        <li>ضريبة القيمة المضافة: {{ number_format($payment->amount * 0.15, 2) }} ريال</li>
                        <li>الإجمالي: {{ number_format($payment->amount * 1.15, 2) }} ريال</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <th>فترة الإعلان</th>
                <td>
                    من {{ $payment->payable->created_at->format('Y-m-d') }}
                    إلى
                    {{ $payment->payable->featured_until ? $payment->payable->featured_until->format('Y-m-d') : 'غير متوفر' }}
                </td>
            </tr>
        </table>
    </div>

    <div class="barcode">
        <!-- هنا يمكن إضافة صورة الباركود -->
        *{{ $payment->transaction_id }}*
    </div>

    <div class="qr-code">
        <!-- هنا يمكن إضافة صورة QR -->
    </div>

    <div class="footer">
        <p>{{ $settings->name }}</p>
        <p>{{ $settings->address ?? '' }}</p>
        <p>هاتف: {{ $settings->phone ?? '' }}</p>
        <p>البريد الإلكتروني: {{ $settings->email ?? '' }}</p>
        <p>تم إصدار هذه الفاتورة إلكترونياً وهي معتمدة بدون توقيع أو ختم.</p>
    </div>
</body>

</html>