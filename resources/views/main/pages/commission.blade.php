{{-- filepath: resources/views/main/pages/commission.blade.php --}}
@extends('main.layouts.app')

@section('content')
    <div class="show-ad-content">
        <div class="layer">
            <div class="container text-right">
                <div class="container">
                    <div class="justify-content-center row">
                        <div class="col-lg-6 col-md-8">
                            <div class="border border-radius-medium mt-5 p-4"
                                style="border: 1px solid #dee2e6; border-radius: 15px; background: white;">

                                {{-- Alert Message --}}
                                <div class="alert mb-4 text-center"
                                    style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 8px; font-size: 14px;">
                                    انتبه، انت هنا تدفع عمولة الموقع 2.5% فقط، أية اتفاقات أخرى تكون بينك وبين الطرف الآخر
                                </div>

                                {{-- Commission Input Section --}}
                                <div class="text-center mb-4">
                                    <h5 class="mb-3" style="color: #333; font-weight: 500; font-size: 16px;">
                                        لمعرفة مبلغ العمولة، أدخل المبلغ الذي تم بنجاح
                                    </h5>

                                    <form id="commissionForm" action="{{ url('/api/payment') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="secure_ref" value="{{ encrypt(auth()->id()) }}" />
                                        <div class="form-group mb-4">
                                            <input type="number" class="form-control" id="commission_value" name="con_val"
                                                placeholder="أدخل المبلغ" required
                                                style="text-align: right; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: #f8f9fa;">
                                        </div>

                                        {{-- Required Amount Display --}}
                                        <div class="py-4 text-center">
                                            <h3 class="mb-2" style="color: #333; font-weight: 600; font-size: 18px;">المبلغ
                                                المطلوب</h3>
                                            <div class="the_result mb-1"
                                                style="font-size: 48px; font-weight: bold; color: #333;">0</div>
                                            <input name="value" type="hidden" />
                                            <h2 style="color: #007bff; font-size: 24px; font-weight: 500; margin: 0;">ريال
                                            </h2>
                                        </div>

                                        {{-- Action Buttons --}}
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn w-100 mb-2" id="payNowBtn"
                                                style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); color: white; padding: 15px; border-radius: 8px; border: none; font-size: 16px; font-weight: 600;">
                                                ادفع الآن
                                            </button>

                                            <a href="{{ route('the_page', 'bank_account') }}" class="btn w-100"
                                                style="background: white; color: #6c757d; border: 2px solid #6c757d; padding: 15px; border-radius: 8px; text-decoration: none; font-size: 16px; font-weight: 500; display: block; text-align: center;">
                                                الدفع عبر حساباتنا
                                            </a>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom styles to match the exact design */
        .alert {
            background-color: #f8d7da !important;
            border-color: #f5c6cb !important;
            color: #721c24 !important;
            border-radius: 8px !important;
            font-size: 14px !important;
            line-height: 1.4 !important;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn:hover {
            transform: translateY(-1px);
            transition: all 0.2s;
        }

        .the_result {
            font-size: 48px !important;
            font-weight: bold !important;
            color: #333 !important;
            line-height: 1 !important;
        }

        /* Button hover effects */
        button[type="submit"]:hover {
            background: linear-gradient(135deg, #375a7f 0%, #1e3a5f 100%) !important;
        }

        .btn:last-child:hover {
            background: #6c757d !important;
            color: white !important;
        }
    </style>
@endsection

@section('footer')
    <script>
        $(document).ready(function () {
            $('#commission_value').on('input keyup', function () {
                const value = parseFloat($(this).val()) || 0;
                const commission = (value * {{ $settings->commission_percentage ?? 2.5 }}) / 100;

                $('.the_result').text(commission.toFixed(2));
                $('input[name="value"]').val(commission.toFixed(2));

                // Enable/disable button based on value
                if (value > 0) {
                    $('#payNowBtn').prop('disabled', false).css('opacity', '1');
                } else {
                    $('#payNowBtn').prop('disabled', true).css('opacity', '0.6');
                }
            });

            // Initially disable the button
            $('#payNowBtn').prop('disabled', true).css('opacity', '0.6');
        });
    </script>
@endsection