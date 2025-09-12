@extends('main.layouts.app')

<script src="https://www.google.com/recaptcha/api.js?onload=correctCaptcha" async defer></script>


@section('content')

    <!-- Contact us block -->
    <section class="form-block my-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="border border-radius-medium p-5">
                        <h3 class="h3 text-dark-heading mb-3">
                            تواصل معنا
                        </h3>
                        <form action="{{route('submit_contact_us')}}" method="post" id="contact-form">
                            @csrf
                            <div class="f">

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div><br />
                                @endif

                                @if (\Session::has('success'))
                                    <div class="alert alert-success">
                                        <ul>
                                            {{\Session::get('success')}}
                                        </ul>
                                    </div><br />
                                @endif

                                <div class="needs_validation">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control name" name="name">
                                        <label for="floatingInput">اسم المستخدم </label>
                                        <span class="name-contact-error invalid-feedback" role="alert"></span>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control email" name="email">
                                        <span class="email-contact-error invalid-feedback" role="alert"></span>
                                        <label for="floatingInput">البريد الالكتروني</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control mobile" name="mobile">
                                        <span class="mobile-contact-error invalid-feedback" role="alert"></span>
                                        <label for="floatingPassword"> رقم الهاتف</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <select name="sort" class="form-control sort" class="form-control">
                                            <option value="مشكلة">مشكلة تقنية</option>
                                            <option value="تسويق">التواصل مع الادارة</option>
                                        </select>
                                        <label for="floatingSelect">نوع الرسالة</label>
                                    </div>
                                    <div class="form-floating  mb-3">
                                        <textarea name="body" class="form-control body" id="body" cols="30" rows="7"
                                            placeholder="Write your notes or questions here..."></textarea>
                                        <span class="body-contact-error invalid-feedback" role="alert"></span>
                                        <label for="floatingTextarea2">محتوى الرسالة</label>
                                    </div>



                                     <div class="mt-3">
                                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site') }}"
                                            data-callback="correctCaptcha"></div>
                                        @if($errors->has('captcha'))
                                            <div class="text-danger mt-2">{{ $errors->first('captcha') }}</div>
                                        @endif
                                        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response" />
                                    </div>

                                    

                                    <button type="submit" class="btn main-btn blue-btn p-3 rounded w-100 mb-2 db_send">
                                        ارسال
                                    </button>

                                   
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--  -->



@endsection



@section('footer')

    {{--
    <script src="http://localhost:35729/livereload.js?snipver=1"></script>--}}

    <script>


        function sendContact() {
            form_data.append('name', $(".name").val())
            form_data.append('mobile', $(".mobile").val())
            form_data.append('email', $(".email").val())
            form_data.append('body', $(".body").val())
            form_data.append('sort', $(".sort").val())
            $(".name").removeClass('is-invalid');
            $(".email").removeClass('is-invalid');
            $(".mobile").removeClass('is-invalid');
            $(".body").removeClass('is-invalid');

            axios.post('../sendcontact', form_data)
                .then((data) => {

                    $(".name").val('');
                    $(".mobile").val('');
                    $(".email").val('');
                    $(".body").val('');


                    $(".name").removeClass('is-invalid');
                    $(".email").removeClass('is-invalid');
                    $(".mobile").removeClass('is-invalid');
                    $(".body").removeClass('is-invalid');


                    $('.name-contact-error').empty();
                    $('.body-contact-error').empty();
                    $('.mobile-contact-error').empty();
                    $('.email-contact-error').empty();


                    $('#success-message').append('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>تم الإرسال!</strong> الرسالة قد تم إرسالها بنجاح!</div></div>');
                    setTimeout(() => {
                        $(".alert").fadeTo(500, 0).slideUp(500, function () {
                            $(this).remove()
                        });
                    }, 2000);
                }).catch((error) => {
                    $('.name-contact-error').empty();
                    $('.body-contact-error').empty();
                    $('.mobile-contact-error').empty();
                    $('.email-contact-error').empty();

                    console.log(error.response.data.errors.name);
                    if (error.response.data.errors.name) {
                        $('.name-contact-error').append('<strong>' + error.response.data.errors.name + '</strong>');
                        $('.name').addClass('is-invalid')
                    }
                    if (error.response.data.errors.email) {
                        $('.email-contact-error').append('<strong>' + error.response.data.errors.email + '</strong>');
                        $('.email').addClass('is-invalid')
                    }
                    if (error.response.data.errors.mobile) {
                        $('.mobile-contact-error').append('<strong>' + error.response.data.errors.mobile + '</strong>');
                        $('.mobile').addClass('is-invalid')
                    }
                    if (error.response.data.errors.body) {
                        $('.body-contact-error').append('<strong>' + error.response.data.errors.body + '</strong>');
                        $('.body').addClass('is-invalid')
                    }
                });

        }

    </script>

@endsection


@section('scripts')
    <script>
        // Called by Google's reCAPTCHA when user completes the challenge
        function correctCaptcha(token) {
            var el = document.getElementById('g-recaptcha-response');
            if (el) {
                el.value = token;
            }
        }
    </script>
@endsection