{{-- resources/views/ads/promote.blade.php --}}
@extends('main.layouts.app')

@section('header')
    <style>
        .promote-card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
            transition: all .3s ease;
        }

        .promote-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, .08);
        }

        .ad-type-option {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 1rem;
            cursor: pointer;
            transition: all .3s ease;
            margin-bottom: 1rem;
        }

        .ad-type-option:hover {
            border-color: #0d6efd;
            background-color: #f8f9ff;
        }

        .ad-type-option.selected {
            border-color: #0d6efd;
            background-color: #e7f1ff;
        }

        .price-display {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 1.5rem;
        }

        .duration-input {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: .75rem;
        }

        .duration-input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, .1);
        }

        .checkout-btn {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            padding: .75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all .3s ease;
        }

        .checkout-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(40, 167, 69, .3);
        }

        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                {{-- معلومات الإعلان الحالي --}}
                <div class="card promote-card mb-4">
                    <div class="card-header bg-light">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="mb-0">تمييز الإعلان</h5>
                            </div>
                            <div class="col-auto">
                                <a href="" class="btn btn-outline-secondary btn-sm">
                                    <i class="fa fa-arrow-right"></i> العودة
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <img src="{{ $post->img ? $post->img_path : ($post->category->img_path ?? '') }}"
                                    class="img-fluid rounded" alt="{{ $post->title }}">
                            </div>
                            <div class="col-md-9">
                                <h4>{{ $post->title }}</h4>
                                <p class="text-muted mb-2">{{ Str::limit($post->body, 150) }}</p>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <strong>المبلغ المطلوب:</strong> {{ number_format($post->price) }} ريال
                                    </div>
                                    <div class="col-sm-6">
                                        <strong>عدد الشركاء:</strong> {{ $post->partners_no }} شريك
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- نموذج التمييز --}}
                <div class="card promote-card">
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form id="promoteForm" action="{{ route('ads.checkout', $post) }}" method="POST">
                            @csrf

                            {{-- اختيار نوع الإعلان --}}
                            <div class="mb-4">
                                <h5 class="mb-3">
                                    <i class="fa fa-star text-warning"></i>
                                    اختر نوع التمييز
                                </h5>
                                <div class="row">
                                    @foreach($adTypes as $type)
                                        <div class="col-md-6">
                                            <div class="ad-type-option" data-type-id="{{ $type->id }}"
                                                data-base-price="{{ $type->base_price }}">
                                                <input type="radio" name="ad_type_id" id="type_{{ $type->id }}"
                                                    value="{{ $type->id }}" class="d-none" required>
                                                <label for="type_{{ $type->id }}" class="w-100 mb-0 cursor-pointer">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="mb-1">
                                                                @if($type->features['badge'] ?? false)
                                                                    <i class="fa fa-star text-warning"></i>
                                                                @endif
                                                                {{ $type->name }}
                                                            </h6>
                                                            <small class="text-muted">{{ $type->description }}</small>
                                                        </div>
                                                        <div class="text-end">
                                                            <strong class="text-primary">{{ number_format($type->base_price) }}
                                                                ريال</strong>
                                                            <br><small class="text-muted">السعر الأساسي</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- اختيار المدة --}}
                            <div class="mb-4">
                                <h5 class="mb-3">
                                    <i class="fa fa-calendar text-info"></i>
                                    اختر مدة التمييز
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">عدد الوحدات</label>
                                        <input type="number" name="duration_value" class="form-control duration-input"
                                            value="30" min="1" max="365" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">نوع الوحدة</label>
                                        <select name="duration_unit" class="form-control duration-input" required>
                                            <option value="day" selected>يوم</option>
                                            <option value="week">أسبوع</option>
                                            <option value="month">شهر</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- عرض السعر --}}
                            <div id="priceDisplay" class="price-display mb-4" style="display: none;">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="mb-2">تفاصيل التكلفة</h5>
                                        <div id="priceBreakdown"></div>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <h3 class="mb-0">
                                            <span id="totalPrice">0</span> ريال
                                        </h3>
                                        <small>الإجمالي شامل الضريبة</small>
                                    </div>
                                </div>
                            </div>

                            {{-- تفاصيل الطلب --}}
                            <div class="order-details mb-4">
                                <h5 class="mb-3">
                                    <i class="fa fa-info-circle text-primary"></i>
                                    تفاصيل الطلب
                                </h5>
                                <ul class="list-unstyled">
                                    <li><strong>عنوان الإعلان:</strong> {{ $post->title }}</li>
                                    <li><strong>السعر الأساسي:</strong> {{ number_format($post->price) }} ريال</li>
                                    <li><strong>المدة:</strong> <span id="durationText">-</span></li>
                                    <li><strong>الإجمالي:</strong> <span id="totalPriceText">-</span> ريال</li>
                                </ul>
                            </div>

                            {{-- أزرار العمل --}}
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" id="calculateBtn" class="btn btn-outline-primary">
                                    <i class="fa fa-calculator"></i> احسب التكلفة
                                </button>

                                <button type="submit" class="btn btn-success btn-lg checkout-btn" id="proceedBtn" disabled>
                                    <i class="fa fa-credit-card"></i> المتابعة للدفع
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script>
        $(document).ready(function () {
            // التعامل مع اختيار نوع الإعلان
            $('.ad-type-option').click(function () {
                $('.ad-type-option').removeClass('selected');
                $(this).addClass('selected');
                $(this).find('input[type="radio"]').prop('checked', true);
                updatePrice();
            });

            // التعامل مع تغيير المدة
            $('input[name="duration_value"], select[name="duration_unit"]').on('change input', function () {
                if ($('input[name="ad_type_id"]:checked').length > 0) {
                    updatePrice();
                }
            });

            // زر حساب التكلفة
            $('#calculateBtn').click(function () {
                updatePrice();
            });

            function updatePrice() {
                const adTypeId = $('input[name="ad_type_id"]:checked').val();
                const durationValue = $('input[name="duration_value"]').val();
                const durationUnit = $('select[name="duration_unit"]').val();

                if (!adTypeId || !durationValue || !durationUnit) {
                    $('#priceDisplay').hide();
                    $('#proceedBtn').prop('disabled', true);
                    return;
                }

                // إظهار تحميل
                $('.promote-card').addClass('loading');

                $.post('{{ route("ads.calculate-price", $post) }}', {
                    _token: '{{ csrf_token() }}',
                    ad_type_id: adTypeId,
                    duration_value: durationValue,
                    duration_unit: durationUnit
                })
                    .done(function (data) {
                        const unitName = {
                            'day': 'يوم',
                            'week': 'أسبوع',
                            'month': 'شهر'
                        }[data.duration.unit] || data.duration.unit;

                        $('#priceBreakdown').html(`
                    <div class="mb-2">
                        <i class="fa fa-tag"></i> السعر الأساسي: ${data.base_price} ريال
                    </div>
                    <div class="mb-2">
                        <i class="fa fa-calculator"></i> المضاعف: ×${data.multiplier}
                    </div>
                    <div class="mb-0">
                        <i class="fa fa-calendar"></i> المدة: ${data.duration.value} ${unitName}
                    </div>
                `);

                        $('#totalPrice').text(parseFloat(data.total).toLocaleString());
                        $('#durationText').text(`${data.duration.value} ${unitName}`);
                        $('#totalPriceText').text(parseFloat(data.total).toLocaleString());
                        $('#priceDisplay').slideDown();
                        $('#proceedBtn').prop('disabled', false);
                    })
                    .fail(function () {
                        alert('حدث خطأ في حساب السعر، حاول مرة أخرى');
                    })
                    .always(function () {
                        $('.promote-card').removeClass('loading');
                    });
            }

            // منع إرسال النموذج بدون حساب السعر
            $('#promoteForm').submit(function (e) {
                if ($('#proceedBtn').prop('disabled')) {
                    e.preventDefault();
                    alert('يرجى حساب التكلفة أولاً');
                } else {
                    $('#proceedBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> جاري المعالجة...');
                }
            });
        });
    </script>
@endsection