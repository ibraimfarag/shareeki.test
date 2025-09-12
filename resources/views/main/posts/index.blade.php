@extends('main.layouts.app')
@section('header')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            overflow-y: scroll;
        }

        .data {
            z-index: -1;
            margin: 50px 0 50px 0;
        }

        /* Ensure clamping works and text doesn't overlap */
        .line-clamp2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Card layout fixes to avoid text spilling and overlapping */
        .card.box-shadow-medium.border-radius-medium.card-hover {
            display: flex;
            flex-direction: column;
            height: 100%;
            min-height: 0;
            /* allow flex children to shrink properly */
            overflow: hidden;
            position: relative;
            background: #fff;
            padding: 0;
            /* body will handle padding */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.06);
            border-radius: 12px;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
            align-items: stretch;
        }


        @media (max-width: 676px) {


            .card.box-shadow-medium.border-radius-medium.card-hover {
                padding: 0;
                width: 250px;

            }

            .ads-mobile {

                width: 250px
            }

        }


        .card.box-shadow-medium.border-radius-medium.card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
        }

        .card.box-shadow-medium.border-radius-medium.card-hover .card-body {
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            color: #222;
            flex: 1 1 auto;
            /* allow body to grow and push footer to bottom */
            min-height: 0;
            /* important for flex children to be able to shrink on small screens */
        }

        .card.box-shadow-medium.border-radius-medium.card-hover .card-title {
            font-weight: 700;
            margin: 0;
            line-height: 1.25;
            font-size: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            word-break: break-word;
        }

        .card.box-shadow-medium.border-radius-medium.card-hover .card-text {
            margin: 0;
            font-size: 0.95rem;
            color: #444;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            word-break: break-word;
        }

        /* image area keeps aspect ratio and won't stretch card content */
        .card.box-shadow-medium.border-radius-medium.card-hover .card-img-top {
            width: 100%;
            height: 160px;
            /* adjust as needed */
            object-fit: cover;
            display: block;
            flex-shrink: 0;
            /* prevent image from collapsing when available space is small */
        }

        /* price/footer should stick to the bottom and not overlap */
        .card.box-shadow-medium.border-radius-medium.card-hover .card-footer {
            background: transparent;
            border-top: 0;
            padding: 8px 12px;
            flex-shrink: 0;
        }

        .card.box-shadow-medium.border-radius-medium.card-hover .price-footer {
            margin-top: auto;
            padding-top: 6px;
        }

        /* Ensure bootstrap grid columns that contain cards stretch the card so footer is visible */
        .ads-new-cards .col-lg-3,
        .ads-new-cards .col-lg-3>div {
            display: flex;
        }

        .ads-new-cards .col-lg-3 .card {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Swiper specific: make slides show full card and avoid vertical cropping of footer */
        .premium-swiper .swiper-slide {
            display: flex;
            /* align to top so card footer is not pushed outside the visible area */
            align-items: flex-start;
            justify-content: center;
        }

        .premium-swiper .card {
            /* allow card to size naturally so footer is visible */
            height: auto;
            display: flex;
            flex-direction: column;
        }

        /* If markup sets a large inline min-height on the container, ensure slides can expand vertically */
        .premium-swiper {
            min-height: 0 !important;
        }
    </style>
@endsection

@section('content')
    <!-- Header -->
    <header class="homepage-header">
        <div class="main-header main-header_padding text-center">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="h2 text-dark-heading mb-0 txt-rotate" data-period="1800"
                            data-rotate='[ "ابحث واختر الفرصة اللي حابها", "تواصل و اجتمع مع المالك وادرسها", "اتفق معاه و تأكد من دفع عمولة الموقع"]'>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Search section -->
    <section class="search-block">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="search-wrap border-radius-rounded border">
                        <form onsubmit="event.preventDefault(); search()" class="row mb-0">
                            <div class="col-lg-10 col-md-12 pe-0">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-search">
                                            <path
                                                d="M11.742 10.344a6.5 6.5 0 10-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 001.415-1.414l-3.85-3.85a1.007 1.007 0 00-.115-.1zM12 6.5a5.5 5.5 0 11-11 0 5.5 5.5 0 0111 0z" />
                                        </svg>
                                    </span>
                                    <input class="form-control form-control-lg search-icon" id="name" type="text"
                                        placeholder="ابحث عن فرصه" aria-label=".form-control-lg example">
                                </div>
                            </div>
                            <!--<div class="col-lg-2 d-lg-block d-md-none  ps-0" style="">-->
                            <div class="col-lg-2 d-lg-block   ps-0" style="z-index: 9999;">
                                <button class="btn mb-0" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                    بحث متقدم
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--   -->



    <!-- Categories section -->
    <section class="categories-block mb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="collapse" id="collapseExample">
                        <div class="border-bottom mt-3 pb-4">
                            <div class="row">
                                <!-- فلتر المدينة -->
                                <div class="col-lg-4 mb-3">
                                    <h4 class="h4 text-dark-heading">
                                        المدينة
                                    </h4>
                                    <div>
                                        <select class="form-select" name="area_id" id="area_id"
                                            aria-label="Default select example">
                                            <option selected disabled>المدينة</option>
                                            @if(isset($areas) && count($areas))
                                                @foreach ($areas as $area)
                                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-8 mb-3">
                                    <h4 class="h4 text-dark-heading">
                                        المبلغ المطلوب
                                    </h4>
                                    <div>
                                        <input id="price" class="form-control" type="number"
                                            placeholder=" المبلغ المطلوب لا يتعدى">
                                    </div>
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <h4 class="h4 text-dark-heading">
                                        نوع الفرصة
                                    </h4>
                                    <div class="d-flex" id="checkboxs-wrap">
                                        <div class="card border border-radius-rounded bg-gray-light card-transition me-3">

                                        </div>
                                        <div class="card border border-radius-rounded bg-gray-light card-transition me-3">
                                            <div class="card-body p-0">
                                                <input type="radio" class="btn-check" value="0" name="sort[]"
                                                    id="radiooption1" autocomplete="off">
                                                <label
                                                    class="btn btn-checked-rounded f4 card-text text-dark-content mb-0  px-3 py-2"
                                                    for="radiooption1">
                                                    فكرة
                                                </label>
                                            </div>
                                        </div>
                                        <div class="card border border-radius-rounded bg-gray-light card-transition me-3">
                                            <div class="card-body p-0">
                                                <input type="radio" class="btn-check" value="1" name="sort[]"
                                                    id="radiooption2" autocomplete="off">
                                                <label
                                                    class="btn btn-checked-rounded f4 card-text text-dark-content mb-0  px-3 py-2"
                                                    for="radiooption2">
                                                    عمل قائم
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-2 col-4 mt-3">
                                    <button onclick="search()"
                                        class="btn main-btn gold-btn medium-btn rounded-btn me-2 w-100">
                                        بحث
                                    </button>
                                </div>

                                <div class="col-lg-2 col-6 mt-3">
                                    <a href="{{asset('/')}}"
                                        class="btn main-btn gold-btn medium-btn rounded-btn me-2 w-100">
                                        إعادة تعيين
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- the main important categories -->
                @foreach ($categories as $category)
                    <div class="col-auto mt-3 h-100 col-lg-block col-md-none">
                        <div class="card border bg-gray-light card-transition">
                            <div class="card-body p-0">
                                <input type="checkbox" class="btn-check" value="{{$category->id}}" onchange="search()"
                                    name="main_category[]" id="skillsoption{{$category->id}}" autocomplete="off">
                                <label class="btn btn-checked h4 card-text text-dark-content mb-0  px-3 py-2"
                                    for="skillsoption{{$category->id}}">
                                    <div class="d-flex align-items-center">
                                        <div class="sx-icon">
                                            <img src="{{ $category->img_path }}" alt="">
                                        </div>
                                        <h4 class="h4 card-text text-dark-content mb-0 ms-3">{{$category->name}}</h4>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    <!-- Paid Opportunities section -->
    <!-- Section for large screens -->
    <section class="opportunities-block wrap fut-sec d-none d-lg-block" id="services">
        <div class="container">
            <h2 class="h2 text-dark-heading text-left mb-0">الفرص المميزة</h2>
            <div class="car-d">
                <!-- card -->
                @foreach ($paidPosts as $paidPost)
                    <div class="mt-2 margin-right-ads">
                        <a href="{{ route('the_posts.show', $paidPost->id) }}">
                            <div class="card box-shadow-medium border-radius-medium card-hover ad-h px-0 py-0">
                                <img class="card-img-top"
                                    src="{{ !empty($paidPost->img) ? $paidPost->img_path : ($paidPost->category->img_path ?? asset('storage/main/categories/default.jpg')) }}"
                                    alt="...">
                                <div class="card-body" style="padding-right: 5px;">
                                    <h4 class="h4 card-title text-dark-heading mb-3 line-clamp2">
                                        {{ $paidPost->category->name ?? 'غير محدد' }}
                                    </h4>
                                    <h3 class="h4 card-text text-dark-content mb-3 line-clamp2">{{ $paidPost->title }}</h3>
                                </div>
                                <div class="card-footer bg-transparent price-footer pb-4" style="padding-right: 5px;">
                                    <h5 class="h4 text-blue-light-heading mb-3">المبلغ المطلوب
                                        {{ number_format($paidPost->price) }}
                                        ريال
                                    </h5>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach

                @for ($i = $paidPosts->count(); $i < 4; $i++)
                    <div class="mt-2">
                        <a href="{{ route('the_posts.create') }}">
                            <div class="card box-shadow-medium border-radius-medium card-hover empty-card free-space ad-h"
                                style="height: 40vh;">
                                <div class="card-body text-center">
                                    <h4 class="h4 card-title text-dark-heading mb-2">اغتنم الفرصة الآن!</h4>
                                    <p class="card-text text-dark-content mb-0">لاتفوت الاشتراك بباقة VIP وزيادة فرص العثور على
                                        الشركاء المناسبين😎🤝
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endfor
            </div>

        </div>

    </section>

    <!-- Section for mobile screens -->
    <section class="opportunities-block wrap fut-sec d-block d-lg-none" id="services-mobile" style="margin-bottom: 0.5rem;">
        <div class="container" style="padding: 0.2rem 0;">
            <h2 class="h2 text-dark-heading text-center mb-1" style="font-size: 1rem; font-weight: bold; color: #007bff;">
                الفرص المميزة</h2>
            <div class="swiper premium-swiper"
                style="overflow: hidden; min-height: 270px; direction: rtl; position:relative;">
                <!-- autoplay is automatic on mobile (no toggle) -->
                <div class="swiper-wrapper">
                    <!-- card -->
                    @foreach ($paidPosts as $paidPost)
                        <div class="swiper-slide">
                            <div class="card box-shadow-medium border-radius-medium card-hover"
                                style="max-width: 85%; margin: 0 auto;">
                                <a href="{{ route('the_posts.show', $paidPost->id) }}">
                                    <img class="img-ad"
                                        src="{{ !empty($paidPost->img) ? $paidPost->img_path : ($paidPost->category->img_path ?? asset('storage/main/categories/default.jpg')) }}"
                                        alt="...">
                                    <div class="card-body">
                                        <h4 class="h4 card-title text-dark-heading mb-1 line-clamp2">
                                            {{ $paidPost->category->name ?? 'غير محدد' }}
                                        </h4>
                                        <h3 class="h4 card-text text-dark-content mb-1 line-clamp2">{{ $paidPost->title }}</h3>
                                    </div>
                                    <div class="card-footer bg-transparent price-footer">
                                        <h5 class="h4 text-blue-light-heading mb-0">المبلغ المطلوب
                                            {{ number_format($paidPost->price) }}
                                            ريال
                                        </h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach

                    @for ($i = $paidPosts->count(); $i < 4; $i++)
                        <div class="swiper-slide">
                            <a href="{{ route('the_posts.create') }}">
                                <div class="card box-shadow-medium border-radius-medium card-hover empty-card free-space"
                                    style="max-width: 85%; margin: 0 auto;">
                                    <div class="card-body text-center">
                                        <h4 class="h4 card-title text-dark-heading mb-1">اغتنم الفرصة الآن!</h4>
                                        <p class="card-text text-dark-content mb-0">لاتفوت الاشتراك بباقة VIP وزيادة فرص العثور
                                            على
                                            الشركاء المناسبين😎🤝
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endfor
                </div>
                <!-- Add Navigation Arrows -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <!-- Add Pagination -->
                {{-- <div class="swiper-pagination"></div> --}}
            </div>
        </div>
    </section>



    <!--  -->
    <!-- Opportunities section -->
    <section class="opportunities-block wrap position-relative margin-top-30 mb-5" id="services">
        <div class="container">
            <h2 class="h2 text-dark-heading text-left mb-0">الفرص المتاحة</h2>
            <div class="row align-items-center ads-new-cards">
                <!-- card -->
                @foreach ($posts as $post)
                    <div class="col-lg-3 mt-2">

                        <a href="{{ route('the_posts.show', $post->id) }}">
                            <div class="card box-shadow-medium border-radius-medium card-hover">
                                <img src="{{ !empty($post->img) ? $post->img_path : ($post->category->img_path ?? asset('storage/main/categories/default.jpg')) }}"
                                    class="card-img-top" alt="...">
                                <div class="card-body" style="padding-right: 0px;">
                                    <h4 class="h4 card-title text-dark-heading mb-3 line-clamp2">
                                        {{ $post->category->name ?? 'غير محدد' }}
                                    </h4>
                                    <h3 class="h4 card-text text-dark-content mb-3 line-clamp2" style="word-wrap: break-word;">
                                        {{ $post->title }}
                                    </h3>
                                </div>
                                <div class="card-footer bg-transparent price-footer" style="padding-right: 0px;">
                                    <h5 class="h4 text-blue-light-heading mb-0 text-wrap">المبلغ المطلوب
                                        {{ number_format($post->price) }} ريال
                                    </h5>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
                <!-- card -->

            </div>

        </div>

    </section>
    <!--  -->

    <!-- Loading secreen -->
    <section class="loading">
        <div class="loding-wrap">
            <div class="spinner-border main-color" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </section>


    <!-- pagination -->
    <div class="container deletePagination mar-18">
        <div class="d-flex justify-content-center">
            <nav aria-label="Page navigation example">
                <ul class="pagination pagination-sm" style="max-width: 100%; overflow-x: auto;">
                    {{ $posts->links('pagination::bootstrap-4') }}
                </ul>
            </nav>
        </div>
        <style>
            @media (max-width: 576px) {
                .deletePagination {
                    max-width: 100%;
                    width: 100%;
                    padding-left: 0;
                    padding-right: 0;
                    overflow-x: auto;
                }

                .pagination {
                    font-size: 0.85rem;
                    flex-wrap: nowrap;
                    width: 100%;
                    min-width: 340px;
                    overflow-x: auto;
                    display: flex;
                }
            }
        </style>
    </div>
    <!--  -->
@endsection

@section('footer')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script>

        $(document).ready(function () {
            let itemone = {};
            itemone.value = 15;
            getSubCities(itemone);

        });

        function getSubCities(item) {
            axios.get('../areas/' + item.value)
                .then((data) => {
                    $('#area_id').empty();
                    $('#area_id').append('<option selected disabled>المدينة</option>');
                    for (subcity of data.data) {
                        $('#area_id').append('<option value="' + subcity.id + '">' + subcity.name + '</option>')
                    }
                })
        }

        function getSubCategories(item) {
            axios.get('../list_subcategories/' + item.value)
                .then((data) => {
                    $('#category_id').empty()
                    for (subcity of data.data) {
                        $('#category_id').append('<option value="' + subcity.id + '">' + subcity.name + '</option>')
                    }
                })
        }
    </script>


    <script>

        function reset() {
            $('#the_country option').prop('selected', function (e) {
                return this.defaultSelected;
            });

            $('#area_id').find('option').remove().end().append('<option selected disabled>المدينة</option>');

            //   $('#sort option').prop('selected', function(e) {
            //         return this.defaultSelected;
            //     });

            $('#main_category option').prop('selected', function (e) {
                return this.defaultSelected;
            });

            $('#category_id').find('option').remove().end().append('<option selected disabled>المجالات الإستثمارية</option>');

            $('#the_tags option').prop('selected', function (e) {
                return this.defaultSelected;
            });

            $('input[type=checkbox]').prop('checked', false);

            $("#name").val("");
            $("#price").val("");
            $("#partners_no").val("");
            $("#weeks_hours").val("");


            search();

        }

        function search() {
            console.log(417, $('input[name="main_category[]"]:checked').serialize())
            let filters =
            {
                'name': $('#name').val(),
                'area_id': $('#area_id').val(),
                'main_category': $('input[name="main_category[]"]:checked').serialize(),
                'category_id': $('input[name="category_id[]"]:checked').serialize(),
                'sort': $('input[name="sort[]"]:checked').serialize(),
                'partners_no': $('#partners_no').val(),
                'price': $('#price').val(),
                'weeks_hours': $('#weeks_hours').val(),
            };

            @auth
                                                                                                                                            var userVerified = {!! date("Y-m-d", strtotime(auth()->user()->email_verified_at)) !!};
                if (userVerified == 1968) {
                    swalMessageIfUnauthenticatedOne();
                    return;
                }
            @endauth
            console.log(filters)
            axios.post('../search', filters)
                .then(function (response) {
                    if (response.data.html == "") {
                        $('.ads-new-cards').html(`<div class=\"ads-cards\">
                                                                                <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                                                                                  <p class="h4">
                                                                                  لا توجد اي معلومات مطابقة
                                                                                  </p>
                                                                               </div>
                                                                                </div>`);
                    } else {
                        $('.ads-new-cards').html(response.data.html);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            return false;
        }

        $('.dropdown-menu option, .dropdown-menu select').click(function (e) {
            e.stopPropagation();
        });


        $('.dropdown-toggle').on("click", function () {
            if ($('.dropdown-toggle').attr('aria-expanded') == 'false') { $('.dropdown-toggle').attr('aria-expanded'); $('.ads-cards').css('opacity', '0.5'); };
        });


        $('body').on("click", function () {
            if ($('.dropdown-toggle').attr('aria-expanded') == 'true') { $('.ads-new-cards').css('opacity', '1'); };
        });

        function swalMessageIfUnauthenticated() {
            Swal.fire({
                icon: 'error',
                position: 'center',
                type: 'error',
                title: "تنبيه",
                html:
                    '<h5>الرجاء تسجيل الدخول أو الإنضمام للموقع</h5> <br/>' +
                    '<a class="btn btn-info" href="{{ route("login") }}">دخول الموقع</a> ' +
                    '<a class="btn btn-info" href="{{ route("register") }}">الإنضمام للموقع</a> ' +
                    '<a class="btn btn-info" onclick="swal.closeModal(); return false;">شكراً ... ربما لاحقاً</a> ',
                showConfirmButton: false,

            })
        }


        function swalMessageIfUnauthenticatedOne() {
            Swal.fire({
                icon: 'error',
                position: 'center',
                type: 'error',
                title: "تنبيه",
                html:
                    '<h5>الرجاء تفعيل الحساب الخاص  بك</h5> <br/> ' +
                    '<a class="btn btn-info" href="{{ route("verification.notice") }}">تفعيل الحساب</a> ',
                showConfirmButton: false,

            })
        }
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // إعدادات Swiper للفرص المميزة في الموبايل
            // Autoplay: always enabled by default on mobile
            const premiumSwiper = new Swiper('.premium-swiper', {
                slidesPerView: 1.2,
                spaceBetween: 15,
                centeredSlides: true,
                loop: true,
                autoplay: { delay: 4000, disableOnInteraction: false },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    dynamicBullets: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                // تحسين الأداء واللمس
                touchRatio: 1,
                touchAngle: 45,
                grabCursor: true,
                effect: 'slide',
                speed: 400,
                // إعدادات للشاشات المختلفة
                breakpoints: {
                    320: {
                        slidesPerView: 1.1,
                        spaceBetween: 10,
                    },
                    480: {
                        slidesPerView: 1.3,
                        spaceBetween: 15,
                    },
                    640: {
                        slidesPerView: 1.5,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 25,
                    },
                },
                // لدعم اللغة العربية
                on: {
                    init: function () {
                        console.log('Swiper للفرص المميزة تم تحميله بنجاح');
                    }
                },
            });
        });
    </script>

    <style>
        /* تحسين مظهر Swiper للموبايل */
        .premium-swiper {
            padding-bottom: 50px !important;
        }

        .premium-swiper .swiper-slide {
            transition: transform 0.3s ease;
        }

        .premium-swiper .swiper-slide-active {
            transform: scale(1.02);
        }

        .premium-swiper .swiper-pagination {
            bottom: 10px !important;
        }

        .premium-swiper .swiper-pagination-bullet {
            background: #007bff;
            opacity: 0.5;
        }

        .premium-swiper .swiper-pagination-bullet-active {
            opacity: 1;
            background: #0056b3;
        }

        .premium-swiper .swiper-button-next,
        .premium-swiper .swiper-button-prev {
            color: #007bff;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            width: 35px;
            height: 35px;
            margin-top: -17px;
        }

        .premium-swiper .swiper-button-next:after,
        .premium-swiper .swiper-button-prev:after {
            font-size: 14px;
            font-weight: bold;
        }

        @media (max-width: 576px) {

            .premium-swiper .swiper-button-next,
            .premium-swiper .swiper-button-prev {
                width: 30px;
                height: 30px;
                margin-top: -15px;
            }

            .premium-swiper .swiper-button-next:after,
            .premium-swiper .swiper-button-prev:after {
                font-size: 12px;
            }
        }
    </style>
@endsection