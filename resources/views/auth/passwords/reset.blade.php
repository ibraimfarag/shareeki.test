<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="apple-touch-icon" href="./images/shareeki-fivicon.png" sizes="180x180">
   <link rel="icon" href="./images/shareeki-fivicon.png" sizes="32x32" type="image/png">
   <link rel="icon" href="./images/shareeki-fivicon.png" sizes="16x16" type="image/png">
   <title>تغيير كلمة المرور</title>
   <link rel="preconnect" href="https://fonts.gstatic.com">
   <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap"
      rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
   <link rel="stylesheet" href="{{ asset('main/css/bootstrap-select.min.css') }} ">
   <link rel="stylesheet" href="{{ asset('main/css/bootstrap.rtl.min.css') }} ">
   <link rel="stylesheet" href="{{ asset('main/css/style.css') }} ">
   <script src="https://www.google.com/recaptcha/api.js?onload=checkcaptcha" async defer></script>

</head>

<body>



   <!-- log in block -->

   <main class="form-block d-flex align-items-center pt-5">

      <div class="container">
         <div class="row justify-content-center">
            <div class="col-lg-6">
               <div class="d-flex align-items-center justify-content-start mb-3">
                  <a class="navbar-brand md-logo" href="{{ asset('/') }}">
                     <img class="_logo" src="{{ asset('main/images/shareeki-logo.png') }} " alt="">
                  </a>
               </div>
               <div class="border border-radius-medium p-5">
                  <h3 class="h3 text-dark-heading mb-3">تغيير كلمة المرور</h3>
                  @if (session('status'))
                     <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                     </div>
                  @endif
                  <div class="f">
                     <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-floating mb-3">
                           <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                              name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                           <label for="floatingInput">البريد الالكتروني</label>
                           @error('email')
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                           @enderror
                        </div>

                        <div class="form-floating mb-3">
                           <input id="password" type="password"
                              class="form-control @error('password') is-invalid @enderror" name="password" required
                              autocomplete="new-password">

                           <label for="floatingPassword">ادخل كلمة المرور</label>
                           @error('password')
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                           @enderror

                        </div>
                        <div class="form-floating">
                           <input id="password-confirm" type="password" class="form-control"
                              name="password_confirmation" required autocomplete="new-password">
                           <label for="floatingPassword">تأكيد كلمة المرور</label>

                        </div>

                        
                        <div class="d-block form-group m-auto mb-4 mt-4 position-relative w-75">
                           <div class="g-recaptcha" data-callback="correctCaptcha"
                              data-sitekey="{{ config('services.recaptcha.site') }}"></div>

                        </div>


                        <button type="submit" class="btn main-btn blue-btn p-3 rounded w-100 mb-2 do_login " disabled>
                           إرسال
                        </button>
                     </form>

                  </div>
               </div>
            </div>
         </div>
      </div>
   </main>


   <script src="{{ asset('main/js/jquery.min.js') }}"></script>
   <script src="{{ asset('main/js/bootstrap.bundle.min.js') }}"></script>
   <script src="{{ asset('main/js/bootstrap-select.min.js') }}"></script>
   <script src="{{ asset('main/js/index.js') }}"></script>
   {{--
   <script src="http://localhost:35729/livereload.js?snipver=1"></script>--}}
   <script type="text/javascript">
      var login = $('.do_login');
      var correctCaptcha = function (response) {
         if (response == 0) {

         } else {
            login.prop('disabled', false);
         }
      };


      //reCaptch verified
   </script>
</body>

</html>