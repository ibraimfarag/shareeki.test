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

        .line-clamp2 {
            overflow: inherit !important;
            white-space: inherit;
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

    <!-- advanced search in mobile -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasBottom" aria-labelledby="offcanvasBottomLabel">
        <div class="offcanvas-header">
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mt-3 pb-4">
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <h4 class="h4 text-dark-heading">
                                        الدولة
                                    </h4>
                                    <div>
                                        <select class="form-select" aria-label="Default select example">
                                            <option selected disabled>الدولة</option>
                                            <option value="1">One</option>
                                            <option value="2">Two</option>
                                            <option value="3">Three</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <h4 class="h4 text-dark-heading">
                                        المدينة
                                    </h4>
                                    <div>
                                        <select class="form-select" aria-label="Default select example">
                                            <option selected disabled>المدينة</option>
                                            <option value="1">One</option>
                                            <option value="2">Two</option>
                                            <option value="3">Three</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <h4 class="h4 text-dark-heading">
                                        عدد ساعات التفرغ بالأسبوع
                                    </h4>
                                    <div>
                                        <input class="form-control" type="number" placeholder="أقصى عدد ساعات عمل بالأسبوع">
                                    </div>
                                </div>
                                <div class="col-lg-8 mb-3">
                                    <h4 class="h4 text-dark-heading">
                                        المبلغ المطلوب
                                    </h4>
                                    <div>
                                        <input class="form-control" type="number" placeholder=" المبلغ المطلوب لا يتعدى">
                                    </div>
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <h4 class="h4 text-dark-heading">
                                        نوع الفرصة
                                    </h4>
                                    <div class="d-flex" id="checkboxs-wrap">
                                        <div class="card border border-radius-rounded bg-gray-light card-transition me-3">
                                            <div class="card-body p-0">
                                                <input type="checkbox" class="btn-check" name="options" id="option3"
                                                    autocomplete="off">
                                                <label
                                                    class="btn btn-checked-rounded h4 card-text text-dark-content mb-0  px-3 py-2"
                                                    for="option3">
                                                    عمل قائم
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                                <!-- @foreach ($categories as $category)-->
                                <!-- <h1>Where is it</h1>-->
                                <!--<div class="col-auto mt-3 h-100">-->
                                <!--    <div class="card border bg-gray-light card-transition">-->
                                <!--        <div class="card-body p-0">-->
                                <!--        <input type="checkbox" class="btn-check" value="{{$category->id}}" name="skillsoption" id="skillsoption{{$category->id}}"-->
                                <!--            autocomplete="off">-->
                                <!--        <label class="btn btn-checked h4 card-text text-dark-content mb-0  px-3 py-2"-->
                                <!--            for="skillsoption{{$category->id}}">-->
                                <!--            <div class="d-flex align-items-center">-->
                                <!--                <div class="sx-icon">-->
                                <!--                    <img src="{{ asset('main/images/'.$category->image) }}" alt="">-->
                                <!--                </div>-->
                                <!--                <h4 class="h4 card-text text-dark-content mb-0 ms-3">{{$category->name}}</h4>-->
                                <!--            </div>-->
                                <!--        </label>-->
                                <!--        </div>-->
                                <!--    </div>-->
                                <!--</div>-->
                                <!--@endforeach-->

                                <div class="col-lg-2 col-sm-12 mt-3">
                                    <button class="btn main-btn gold-btn medium-btn rounded-btn me-2 w-100">
                                        بحث
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories section -->
    <section class="categories-block mb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="collapse" id="collapseExample">
                        <div class="border-bottom mt-3 pb-4">
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <h4 class="h4 text-dark-heading">
                                        الدولة
                                    </h4>
                                    <div>
                                        <select id="the_country" onchange="getSubCities(this);" class="form-select"
                                            aria-label="Default select example">
                                            <option selected disabled>الدولة</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <h4 class="h4 text-dark-heading">
                                        المدينة
                                    </h4>
                                    <div>
                                        <select class="form-select" name="area_id" id="area_id"
                                            aria-label="Default select example">
                                            <option selected disabled>المدينة</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <h4 class="h4 text-dark-heading">
                                        عدد ساعات التفرغ بالأسبوع
                                    </h4>
                                    <div>
                                        <input class="form-control" type="number" placeholder="أقصى عدد ساعات عمل بالأسبوع"
                                            id="weeks_hours" class="form-control @error('weeks_hours') is-invalid @enderror"
                                            name="weeks_hours" value="{{ old('weeks_hours') }}" required
                                            autocomplete="weeks_hours">
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
                                <div class="col-2 mt-3">
                                    <button onclick="search()"
                                        class="btn main-btn gold-btn medium-btn rounded-btn me-2 w-100">
                                        بحث
                                    </button>
                                </div>

                                <div class="col-2 mt-3">
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
                                            <img src="{{ asset('storage/main/categories/' . $category->image) }}" alt="">
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

    @if ($paidPosts->count() > 0)
        <section class="opportunities-block wrap position-relative mt-3 mb-5" id="services">
            <div class="container">
                <h2 class="h2 text-dark-heading text-left mb-0">الفرص المميزة</h2>
                <div class="row align-items-center ads-new-cards">
                    <!-- card -->
                    @foreach ($paidPosts as $paidPost)
                        <div class="col-lg-3 mt-2">

                            <a href="{{ route('the_posts.show', $paidPost->id) }}">
                                <div class="card box-shadow-medium border-radius-medium card-hover">
                                    <img src="{{ $paidPost->img != null ? $paidPost->img_path : $paidPost->category->img_path ?? '' }}"
                                        class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h4 class="h4 card-title text-dark-heading mb-2 line-clamp2">
                                            {{ $paidPost->category->name ?? 'غير محدد' }}</h4>
                                        <h3 class="h4 card-text text-dark-content mb-0 line-clamp2">{{ $paidPost->title }}</h3>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <h5 class="h4 text-blue-light-heading">المبلغ المطلوب {{ number_format($paidPost->price) }}
                                            ريال</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                    <!-- card -->

                </div>

            </div>

        </section>
        
    @endif



    <!--  -->
    <!-- Opportunities section -->
    <section class="opportunities-block wrap position-relative mt-3 mb-5" id="services">
        <div class="container">
            <h2 class="h2 text-dark-heading text-left mb-0">الفرص المتاحة</h2>
            <div class="row align-items-center ads-new-cards">
                <!-- card -->
                @foreach ($posts as $post)
                    <div class="col-lg-3 mt-2">

                        <a href="{{ route('the_posts.show', $post->id) }}">
                            <div class="card box-shadow-medium border-radius-medium card-hover">
                                <img src="{{ $post->img != null ? $post->img_path : $post->category->img_path ?? '' }}"
                                    class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h4 class="h4 card-title text-dark-heading mb-2 line-clamp2">
                                        {{ $post->category->name ?? 'غير محدد' }}</h4>
                                    <h3 class="h4 card-text text-dark-content mb-0 line-clamp2">{{ $post->title }}</h3>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <h5 class="h4 text-blue-light-heading">المبلغ المطلوب {{ number_format($post->price) }} ريال
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
    <div class="container deletePagination">
        <div class="d-flex justify-content-center">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    {{ $posts->links('pagination::bootstrap-4') }}


                </ul>
            </nav>
        </div>
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
@endsection