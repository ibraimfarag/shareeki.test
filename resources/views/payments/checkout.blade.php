@extends('main.layouts.app')

@section('header')
    <!-- إضافة SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .payment-page {
            padding: 40px 0;
        }

        .payment-card {
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .payment-header {
            background: linear-gradient(45deg, #2196F3, #1976D2);
            padding: 20px;
            color: white;
        }

        .payment-body {
            padding: 30px;
        }

        .payment-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .card-input {
            border: 1px solid #ddd;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .card-input:focus {
            border-color: #2196F3;
            box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, .25);
        }

        .btn-pay {
            background: linear-gradient(45deg, #4CAF50, #45a049);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .payment-info {
            font-size: 14px;
            color: #666;
            margin-top: 20px;
        }

        .secure-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #45a049;
            margin-top: 15px;
        }
    </style>
@endsection

@section('content')
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="payment-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="payment-card">
                        <div class="payment-header">
                            <h3 class="mb-0">إتمام عملية الدفع</h3>
                            <p class="mb-0">تمييز الإعلان</p>
                        </div>

                        <div class="payment-body">
                            <!-- ملخص الدفع -->
                            <div class="payment-summary">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>تفاصيل الطلب</h5>
                                        <p>تمييز الإعلان: {{ $payment->description }}</p>
                                        <p>رقم الطلب: #{{ $payment->id }}</p>
                                        <p>الفترة:
                                            @if($payment->payable && $payment->payable->created_at && $payment->payable->featured_until)
                                                من {{ $payment->payable->created_at->format('Y-m-d') }}
                                                إلى {{ $payment->payable->featured_until->format('Y-m-d') }}
                                            @else
                                                من {{ now()->format('Y-m-d') }}
                                                إلى {{ now()->addMonths(3)->format('Y-m-d') }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-6 text-md-end">
                                        <h5>تفاصيل المبلغ</h5>
                                        <ul class="list-unstyled">
                                            <li>قيمة الإعلان: 130.00 ريال</li>
                                            <li>ضريبة القيمة المضافة: 19.50 ريال</li>
                                            <li><strong>الإجمالي: {{ number_format($payment->amount, 2) }} ريال</strong>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- نموذج الدفع -->
                            <form id="paymentForm" method="POST" action="{{ route('payments.process') }}">
                                @csrf
                                <input type="hidden" name="payment_id" value="{{ $payment->id }}">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>الاسم على البطاقة</label>
                                            <input type="text" class="form-control card-input" name="card_name" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>رقم البطاقة</label>
                                            <input type="text" class="form-control card-input" name="card_number"
                                                id="cardNumber" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>تاريخ الانتهاء</label>
                                            <input type="text" class="form-control card-input" name="expiry" id="expiry"
                                                placeholder="MM/YY" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>رمز الأمان (CVV)</label>
                                            <input type="text" class="form-control card-input" name="cvv" id="cvv" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn-pay">
                                        إتمام الدفع الآن
                                    </button>
                                </div>

                                <div class="secure-badge text-center">
                                    <i class="fas fa-lock"></i>
                                    <span>جميع المعاملات مشفرة وآمنة</span>
                                </div>
                            </form>

                            <div class="payment-info">
                                <p class="mb-1">* سيتم تمييز إعلانك مباشرة بعد نجاح عملية الدفع</p>
                                <p class="mb-1">* يمكنك إلغاء تمييز الإعلان في أي وقت من لوحة التحكم</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // تنسيق رقم البطاقة
            new Cleave('#cardNumber', {
                creditCard: true,
            });

            // تنسيق تاريخ الانتهاء
            new Cleave('#expiry', {
                date: true,
                datePattern: ['m', 'y']
            });

            // تنسيق CVV
            new Cleave('#cvv', {
                numeral: true,
                numeralPositiveOnly: true,
                blocks: [3]
            });

            // التحقق من النموذج قبل الإرسال
            document.getElementById('paymentForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);

                // عرض مؤشر التحميل
                Swal.fire({
                    title: 'جاري معالجة الدفع',
                    text: 'يرجى الانتظار...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                // إرسال الطلب باستخدام Fetch API
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.requires_otp) {
                            // عرض نموذج OTP
                            Swal.fire({
                                title: 'التحقق من الهوية',
                                text: data.message,
                                input: 'text',
                                inputAttributes: {
                                    autocapitalize: 'off',
                                    maxlength: 6
                                },
                                showCancelButton: true,
                                confirmButtonText: 'تأكيد',
                                cancelButtonText: 'إلغاء',
                                showLoaderOnConfirm: true,
                                preConfirm: (otp) => {
                                    return fetch('/payments/verify-otp', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                        },
                                        body: JSON.stringify({
                                            payment_id: '{{ $payment->id }}',
                                            otp: otp,
                                            otp_reference: data.otp_reference
                                        })
                                    })
                                        .then(response => response.json())
                                        .then(result => {
                                            if (!result.success) {
                                                throw new Error(result.message)
                                            }
                                            return result;
                                        });
                                },
                                allowOutsideClick: () => !Swal.isLoading()
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = result.value.redirect_url;
                                }
                            });
                            return;
                        }

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم الدفع بنجاح!',
                                text: data.message,
                                confirmButtonText: 'حسناً'
                            }).then(() => {
                                window.location.href = data.redirect_url;
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ!',
                                text: data.message || 'حدث خطأ أثناء معالجة الدفع',
                                confirmButtonText: 'حسناً'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ!',
                            text: 'حدث خطأ أثناء الاتصال بالخادم',
                            confirmButtonText: 'حسناً'
                        });
                    });
            });
        });
    </script>
@endsection