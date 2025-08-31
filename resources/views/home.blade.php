@extends('main.layouts.app')
@section('header')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- إضافة Bootstrap CSS و JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body {
            overflow-y: scroll;
        }

        /* تنسيق Tooltip المخصص */
        .custom-tooltip {
            --bs-tooltip-bg: #2d3436;
            --bs-tooltip-color: #fff;
            --bs-tooltip-padding-x: 1rem;
            --bs-tooltip-padding-y: 0.5rem;
            --bs-tooltip-font-size: 0.875rem;
            --bs-tooltip-max-width: 250px;
            font-family: 'Cairo', sans-serif;
        }

        .custom-tooltip .tooltip-inner {
            background: linear-gradient(45deg, #2d3436, #434343);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            border-radius: 6px;
        }

        .tooltip.bs-tooltip-top .tooltip-arrow::before {
            border-top-color: #2d3436 !important;
        }

        /* تنسيق زر التعطيل */
        .disabled-promote {
            opacity: 0.7 !important;
            cursor: not-allowed !important;
            position: relative;
            overflow: hidden;
        }

        .disabled-promote::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.2));
            pointer-events: none;
        }

        .disabled-promote:hover {
            transform: none !important;
            box-shadow: none !important;
        }

        .data {
            z-index: -1;
            margin: 50px 0 50px 0;
        }

        /* بطاقات الإعلانات */
        .profile-card .card {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            transition: transform .15s ease, box-shadow .15s ease;
            height: 100%;
        }

        .profile-card .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .06);
        }

        /* صورة الإعلان */
        .profile-card .ads-img {
            width: 100%;
            height: 180px;
            border-radius: 8px;
            overflow: hidden;
            background: #f7f7f7;
            margin-bottom: 10px;
        }

        .profile-card .ads-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* عناوين ونصوص */
        .profile-card .ad-name a {
            color: #111;
            font-weight: 700;
            font-size: 15px;
            text-decoration: none;
        }

        .profile-card .ad-name a:hover {
            color: #0d6efd;
        }

        /* الشارات */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
            padding: .4rem .6rem;
            border-radius: 999px;
            font-size: 12px;
        }

        .badge-status i {
            font-size: 12px;
        }

        .badge-paid {
            background: #e7f7ef;
            color: #13795b;
        }

        .badge-pending {
            background: #fff5e6;
            color: #b77400;
        }

        .badge-expired {
            background: #ffe9ea;
            color: #b4232a;
        }

        .badge-free {
            background: #eef1f5;
            color: #334155;
        }

        /* مجموعة الأزرار أسفل الكارد */
        .action-bar {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
            justify-content: flex-start;
        }

        .btn-rounded {
            border-radius: 8px !important;
            font-weight: 600;
            padding: .45rem .7rem;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            box-shadow: 0 1px 0 rgba(16, 24, 40, .04);
            font-size: 12px;
        }

        /* ألوان أزرار محسنة */
        .btn-promote {
            background: linear-gradient(180deg, #ffc107, #ffb300);
            color: #222;
            border: 1px solid #f5b300;
        }

        .btn-promote:hover {
            filter: brightness(.98);
            color: #111;
        }

        .btn-edit {
            background: #0d6efd;
            border: 1px solid #0b5ed7;
            color: #fff;
        }

        .btn-edit:hover {
            background: #0b5ed7;
            color: #fff;
        }

        .btn-delete {
            background: #ef4444;
            border: 1px solid #dc3545;
            color: #fff;
        }

        .btn-delete:hover {
            background: #dc2626;
            color: #fff;
        }

        /* تخطيط صف البطاقات مع RTL */
        .row-cards {
            row-gap: 1rem;
        }

        @media (max-width: 576px) {
            .action-bar {
                gap: .4rem;
                flex-direction: column;
            }

            .btn-rounded {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endsection

@section('content')
    <!-- proflie-setings -->
    <section class="proflie-setings mt-3 pt-4">
        <div class="container">
            <h2 class="h2 text-dark-heading mb-3">الملف الشخصي</h2>
            <div class="row">
                <div class="col-lg-3 col-md-12 my-2">
                    <a href="{{ route('the_posts.create') }}">
                        <div
                            class="card bg-gold-light card-transition border-dashed-gold p-4 text-center border-radius-medium h-100">
                            <div class="sx-icon crlc-icon bg-gold-300  p-2 mb-3 mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px"
                                    viewBox="0 0 24 24" width="24px" fill="#000000">
                                    <rect fill="none" height="24" width="24" />
                                    <path
                                        d="M22,5.18L10.59,16.6l-4.24-4.24l1.41-1.41l2.83,2.83l10-10L22,5.18z M12,20c-4.41,0-8-3.59-8-8s3.59-8,8-8 c1.57,0,3.04,0.46,4.28,1.25l1.45-1.45C16.1,2.67,14.13,2,12,2C6.48,2,2,6.48,2,12s4.48,10,10,10c1.73,0,3.36-0.44,4.78-1.22 l-1.5-1.5C14.28,19.74,13.17,20,12,20z M19,15h-3v2h3v3h2v-3h3v-2h-3v-3h-2V15z" />
                                </svg>
                            </div>
                            <h4 class="h4 text-dark-heading mb-0">
                                أضف فرصة!
                            </h4>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-12 my-2">
                    <a href="{{ route('get_personal_info') }}">
                        <div class="card box-shadow-medium card-transition p-4 text-center border-radius-medium h-100">
                            <div class="sx-icon crlc-icon bg-gold-light p-2 mb-3 mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px"
                                    fill="#000000">
                                    <path d="M0 0h24v24H0V0z" fill="none" />
                                    <path
                                        d="M12 6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2m0 9c2.7 0 5.8 1.29 6 2v1H6v-.99c.2-.72 3.3-2.01 6-2.01m0-11C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 9c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z" />
                                </svg>
                            </div>
                            <h4 class="h4 text-dark-heading mb-0">
                                تعديل المعلومات الشخصية
                            </h4>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-12 my-2">
                    <a href="{{ route('get_change_password') }}">
                        <div class="card box-shadow-medium card-transition p-4 text-center border-radius-medium h-100">
                            <div class="sx-icon crlc-icon bg-gold-light p-2 mb-3 mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px"
                                    fill="#000000">
                                    <g fill="none">
                                        <path d="M0 0h24v24H0V0z" />
                                        <path d="M0 0h24v24H0V0z" opacity=".87" />
                                    </g>
                                    <path
                                        d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z" />
                                </svg>
                            </div>
                            <h4 class="h4 text-dark-heading mb-0">
                                تعديل كلمة المرور
                            </h4>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-12 my-2">
                    <a href="{{ route('the_posts.index') }}">
                        <div class="card box-shadow-medium card-transition p-4 text-center border-radius-medium h-100">
                            <div class="sx-icon crlc-icon bg-gold-light p-2 mb-3 mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px"
                                    fill="#000000">
                                    <g fill="none">
                                        <path d="M0 0h24v24H0V0z" />
                                        <path d="M0 0h24v24H0V0z" opacity=".87" />
                                    </g>
                                    <path
                                        d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z" />
                                </svg>
                            </div>
                            <h4 class="h4 text-dark-heading mb-0">
                                الفرص المتاحة
                            </h4>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- قسم فرصي -->
    <div class="blank-section mt-5">
        <div class="container">
            <div class="border-top"></div>
            <h2 class="h2 text-dark-heading text-left mb-1 pt-4">فرصي</h2>
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-12">
                    @forelse (auth()->user()->posts as $post)
                        @if ($loop->first)
                            <div class="row row-cards">
                        @endif

                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="card profile-card">
                                    <div class="card-body">
                                        <div class="ads-img">
                                            <img src="{{ $post->img != null ? $post->img_path : ($post->category->img_path ?? '') }}"
                                                alt="{{ $post->title }}">
                                        </div>

                                        <div class="ad-details">
                                            <p class="text-end ad-name mb-2">
                                                <a
                                                    href="{{ route('the_posts.show', $post->id) }}">{{ Str::limit($post->title, 60) }}</a>
                                            </p>

                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-muted">المبلغ المطلوب</small>
                                                <span class="fw-bold">{{ number_format($post->price) }} ريال</span>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <small class="text-muted">عدد الشركاء</small>
                                                <span class="fw-bold">{{ $post->partners_no }} حد أقصى</span>
                                            </div>

                                            @php
                                                $isActive = $post->status === 'active' && (is_null($post->ends_at) || $post->ends_at->gte(now()));
                                                $featuredService = new App\Services\FeaturedPostService();
                                                $canPromote = (!$post->is_paid || !$isActive) && $featuredService->canFeaturePost();
                                            @endphp



                                            {{-- شريط الأزرار --}}
                                            <div class="action-bar mt-3">
                                                @if($canPromote)
                                                    <button onclick="featurePost({{ $post->id }})"
                                                        class="btn btn-promote btn-rounded">
                                                        <i class="fa fa-bullhorn"></i>
                                                        تمييز الإعلان
                                                    </button>
                                                @elseif(!$featuredService->canFeaturePost())
                                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="عذراً، جميع الأماكن المميزة محجوزة حالياً (الحد الأقصى 4 إعلانات)">
                                                        <button class="btn btn-promote btn-rounded disabled-promote"
                                                            style="pointer-events: none;" disabled>
                                                            <i class="fa fa-bullhorn"></i>
                                                            تمييز الإعلان
                                                        </button>
                                                    </span>
                                                @endif

                                                <a href="{{ route('the_posts.edit', $post->id) }}"
                                                    class="btn btn-edit btn-rounded">
                                                    <i class="fa fa-edit"></i>
                                                    تعديل
                                                </a>

                                                <form action="{{ route('the_posts.destroy', $post->id) }}" method="post"
                                                    class="d-inline"
                                                    onsubmit="return confirm('هل أنت متأكد من حذف هذا الإعلان؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-delete btn-rounded" type="submit">
                                                        <i class="fa fa-trash"></i>
                                                        حذف
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($loop->last)
                                </div>
                            @endif
                    @empty
                        <div class="blank-block text-center">
                            <a href="{{ route('the_posts.create') }}">
                                <figure class="blank-figure blank-products-state">
                                    <img src="{{asset('main/images/blank-product-svg.svg')}}" alt="" srcset="">
                                </figure>
                                <figure class="blank-figure add-product-state">
                                    <img src="{{asset('main/images/add-product-svg.svg')}}" alt="" srcset="">
                                </figure>
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Modal ساعدنا نعرف رغبتك -->
    @if(is_null(auth()->user()->max_budget))
        <div class="modal fade" id="helpUs" tabindex="-1" aria-labelledby="helpUsLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header px-4">
                        <h5 class="modal-title" id="helpUsLabel">ساعدنا نعرف رغبتك</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form method="POST" class="row g-3" action="{{ route('update_suggestions') }}">
                            @csrf
                            @method('PATCH')
                            <div class="col-md-12">
                                <label for="city" class="form-label"> مدينة السكن </label>
                                <input type="text" class="form-control" value="{{ auth()->user()->city }}" id="city" name="city"
                                    autocomplete="city" required>
                            </div>
                            <div class="col-md-12">
                                <label for="birth_date" class="form-label"> تاريخ الميلاد </label>
                                <input type="date" class="form-control" value="{{ auth()->user()->birth_date }}"
                                    max="<?= date('Y-m-d'); ?>" id="birth_date" name="birth_date" autocomplete="birth_date"
                                    required>
                            </div>
                            <div class="col-md-12">
                                <label for="mobile" class="form-label"> الجوال </label>
                                <input type="text" class="form-control" value="{{ auth()->user()->mobile }}" id="mobile"
                                    name="mobile" maxlength="15" autocomplete="mobile" required>
                            </div>
                            <div class="col-md-12">
                                <label for="max_budget" class="form-label"> الميزانية القصوى المتاحة للدخول فى الفرص التجارية
                                </label>
                                <input type="number" class="form-control" value="{{ auth()->user()->max_budget }}"
                                    id="max_budget" pattern="/^-?\d+\.?\d*$/"
                                    onKeyPress="if(this.value.length==11) return false;" name="max_budget"
                                    autocomplete="max_budget" required>
                            </div>
                            <div class="modal-footer justify-content-start px-4">
                                <button type="submit" class="btn main-btn gold-btn medium-btn rounded"> إرسال</button>
                                <button type="button" class="btn main-btn blue-btn medium-btn rounded" data-bs-dismiss="modal">
                                    لا شكرا
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('footer')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script type="text/javascript">
        $(window).on('load', function () {
            $('#helpUs').modal('show');

            // تهيئة جميع tooltips في الصفحة
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(tooltip => {
                new bootstrap.Tooltip(tooltip, {
                    html: true,
                    container: 'body',
                    trigger: 'hover focus',
                    delay: { show: 50, hide: 50 }
                });
            });
        });

        function featurePost(postId) {
            Swal.fire({
                title: 'تأكيد تمييز الإعلان',
                html: `
                            <div class="text-right">
                                <p>تكلفة تمييز الإعلان: <strong>149.50</strong> ريال</p>
                                <p>يشمل:</p>
                                <ul>
                                    <li><strong>قيمة الإعلان:</strong> 130.00 ريال</li>
                                    <li><strong>ضريبة القيمة المضافة:</strong> 19.50 ريال</li>
                                </ul>
                                <p>الفترة: من {{ now()->format('Y-m-d') }} إلى {{ now()->addMonths(3)->format('Y-m-d') }}</p>
                                <p>سيتم إصدار فاتورة وإيصال تلقائيًا.</p>
                            </div>
                        `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم، تمييز الإعلان',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`/posts/${postId}/feature`)
                        .then(function (response) {
                            if (response.data.success) {
                                if (response.data.redirect_url) {
                                    window.location.href = response.data.redirect_url;
                                } else {
                                    Swal.fire('تم!', response.data.message, 'success')
                                        .then(() => {
                                            location.reload();
                                        });
                                }
                            } else {
                                Swal.fire('خطأ!', response.data.message, 'error');
                            }
                        })
                        .catch(function (error) {
                            console.error('Error:', error);
                            Swal.fire('خطأ!', error.response?.data?.message || 'حدث خطأ أثناء معالجة طلبك', 'error');
                        });
                }
            });
        }
    </script>
@endsection