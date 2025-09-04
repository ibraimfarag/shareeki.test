<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="apple-touch-icon" href="./images/shareeki-fivicon.png" sizes="180x180">
   <link rel="icon" href="./images/shareeki-fivicon.png" sizes="32x32" type="image/png">
   <link rel="icon" href="./images/shareeki-fivicon.png" sizes="16x16" type="image/png">
   <title> تسجيل دخول</title>
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
   <style>
      .iti__country-container {
         left: 0 !important;
      }
   </style>


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
                  <h3 class="h3 text-dark-heading mb-3">تسجيل حساب جديد</h3>
                  <div class="f">
                     <form method="POST" action="{{ route('register') }}" novalidate>
                        {{-- إضافة هذا السكربت لتحديث قيمة رقم الهاتف قبل الإرسال --}}
                        <script>
                           document.addEventListener('DOMContentLoaded', function () {
                              const phoneInput = document.getElementById('phone');
                              let iti = null;
                              if (window.intlTelInputGlobals && phoneInput) {
                                 iti = window.intlTelInputGlobals.getInstance(phoneInput);
                                 // تحديث قيمة input تلقائياً عند تغيير الدولة أو الرقم
                                 function updatePhoneWithDialCode() {
                                    let phone = iti.getNumber('E.164').replace(/^\+/, '');
                                    phoneInput.value = phone;
                                 }
                                 phoneInput.addEventListener('countrychange', updatePhoneWithDialCode);
                                 phoneInput.addEventListener('blur', updatePhoneWithDialCode);
                              }
                              const form = document.querySelector('form[action*="register"]');
                              if (form) {
                                 form.addEventListener('submit', function (e) {
                                    if (phoneInput && iti) {
                                       let phone = iti.getNumber('E.164').replace(/^\+/, '');
                                       phoneInput.value = phone;
                                       console.log('قيمة الهاتف النهائية المرسلة في الفورم:', phone);
                                    }
                                 });
                              }
                           });
                        </script>
                        @csrf
                        <div class="form-floating mb-3">
                           <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                              name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                           <label for="floatingInput">اسم المستخدم </label>
                           @error('name')
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                           @enderror
                        </div>
                        <input type="hidden" name="phone_full">
                        <input type="hidden" name="country_code">
                        <div class="mb-3">
                           <label for="phone" class="form-label">رقم الجوال</label>
                           <div class="input-group flex-nowrap">
                              <input id="phone" name="phone" type="tel"
                                 class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}"
                                 required autocomplete="tel" dir="ltr" style="text-align:left;">
                              <button type="button" class="btn btn-outline-primary" id="send-code-btn">إرسال كود
                                 التفعيل</button>
                           </div>
                           @error('phone')
                              <span class="invalid-feedback d-block" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                           @enderror
                           <span id="sms-status" class="text-success ms-2"></span>
                        </div>

                        <div class="form-floating mb-3" id="code-section" style="display:none;">
                           <input id="phone_code" type="text"
                              class="form-control @error('phone_code') is-invalid @enderror" name="phone_code"
                              maxlength="4" pattern="[0-9]{4}" autocomplete="off">
                           <label for="phone_code">كود التفعيل</label>
                           @error('phone_code')
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                           @enderror
                        </div>


                        <div class="form-floating mb-3">
                           <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                              name="email" value="{{ old('email') }}" required autocomplete="email">
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

                        <div class="form-check" style="margin-top:20px;">
                           <input class="form-check-input @error('check_terms') is-invalid @enderror" type="checkbox"
                              value="1" name="check_terms" id="check_terms">
                           <label class="form-check-label" for="check_terms">
                              <a href="https://shareeki.net/the_page/terms" target="_blank">الموافقة على الشروط
                                 والأحكام</a>
                           </label>
                           @error('check_terms')
                              <span class="invalid-feedback" role="alert">
                                 <strong>يجب الموافقة على الشروط والأحكام</strong>
                              </span>
                           @enderror
                        </div>



                        <div class="d-flex justify-content-between mt-2 mb-4">
                           <a href="{{ asset('login') }}" class="btn main-btn text-btn px-0">
                              لدي بالفعل حساب
                           </a>
                        </div>
                        <div class="d-block form-group m-auto mb-4 mt-4 position-relative w-75">
                           {{-- <div class="g-recaptcha" data-callback="correctCaptcha"
                              data-sitekey="6Lczcc0cAAAAAN_gSpNpcFude8sz3ggfBQ_wShnW"></div> --}}

                        </div>
                        <button type="submit" class="btn main-btn blue-btn p-3 rounded w-100 mb-2 do_login"
                           id="register-btn" disabled>
                           انشاء حساب جديد
                        </button>
                        <script>
                           document.addEventListener('DOMContentLoaded', function () {
                              var checkTerms = document.getElementById('check_terms');
                              var registerBtn = document.getElementById('register-btn');
                              if (checkTerms && registerBtn) {
                                 checkTerms.checked = false;
                                 checkTerms.addEventListener('change', function () {
                                    registerBtn.disabled = !this.checked;
                                 });
                                 registerBtn.disabled = true;
                              }
                           });
                        </script>
                     </form>


                     <div class="border-top mt-4 pt-3">
                        <h3 class="h3 text-dark-heading mb-3 text-center mt-2">
                           أو التسجيل بواسطة
                        </h3>
                        <div class="btn-group w-100" role="group" aria-label="Basic example">
                           <a href="{{ route('auth.google') }}"
                              class="btn main-btn p-3 rounded blue-btn google-btn me-1 d-flex justify-content-center align-items-center">
                              <span class="d-inline-block me-2">Google</span>

                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="bi bi-google" viewBox="0 0 16 16">
                                 <path
                                    d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z" />
                              </svg>
                           </a>
                           <button href="{{route('auth.facebook')}}"
                              class="btn main-btn p-3 rounded blue-btn facebock-btn ms-1 d-flex justify-content-center align-items-center">
                              <span class="d-inline-block me-2">Facebook</span>
                              <svg height="20" viewBox="0 0 100 100" width="20" xmlns="http://www.w3.org/2000/svg"
                                 fill="currentColor">
                                 <g id="_x30_1._Facebook">
                                    <path id="Icon_11_"
                                       d="m40.4 55.2c-.3 0-6.9 0-9.9 0-1.6 0-2.1-.6-2.1-2.1 0-4 0-8.1 0-12.1 0-1.6.6-2.1 2.1-2.1h9.9c0-.3 0-6.1 0-8.8 0-4 .7-7.8 2.7-11.3 2.1-3.6 5.1-6 8.9-7.4 2.5-.9 5-1.3 7.7-1.3h9.8c1.4 0 2 .6 2 2v11.4c0 1.4-.6 2-2 2-2.7 0-5.4 0-8.1.1-2.7 0-4.1 1.3-4.1 4.1-.1 3 0 5.9 0 9h11.6c1.6 0 2.2.6 2.2 2.2v12.1c0 1.6-.5 2.1-2.2 2.1-3.6 0-11.3 0-11.6 0v32.6c0 1.7-.5 2.3-2.3 2.3-4.2 0-8.3 0-12.5 0-1.5 0-2.1-.6-2.1-2.1 0-10.5 0-32.4 0-32.7z" />
                                 </g>
                              </svg>
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="d-flex justify-content-between mb-3">
                  <div class="d-flex">
                     <a class="nav-link" href="{{ route('the_page', 'terms') }}">
                        الشروط والأحكام
                     </a>
                     <a class="nav-link" href="{{ route('the_page', 'faqs') }}">
                        ارشادات عامّة
                     </a>
                  </div>
                  <a class="nav-link" href="{{ route('contact_us') }}">
                     تواصل معنا
                  </a>
               </div>
            </div>
         </div>
      </div>
   </main>


   <script src="{{ asset('main/js/jquery.min.js') }}"></script>
   <script src="{{ asset('main/js/bootstrap.bundle.min.js') }}"></script>
   <script src="{{ asset('main/js/bootstrap-select.min.js') }}"></script>
   <script src="{{ asset('main/js/index.js') }}"></script>
   <!-- intl-tel-input CSS & JS (jsdelivr latest) -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.10.0/build/css/intlTelInput.css">
   <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.10.0/build/js/intlTelInput.min.js"></script>
   {{--
   <script src="http://localhost:35729/livereload.js?snipver=1"></script>--}}
   <style>
      /* intl-tel-input custom style for RTL */

      [type=tel] {
         direction: ltr;
         text-align: left;
      }

      .iti--allow-dropdown input,
      .iti--allow-dropdown input[type=tel] {
         direction: ltr;
         text-align: left;
      }

      /* جعل كود الدولة (العلم والكود) في الشمال (يسار الحقل) */
      [dir="rtl"] .iti .iti__flag-container {
         right: auto;
         left: 0;
      }

      [dir="rtl"] .iti input {
         padding-right: 0 !important;
         padding-left: 82px !important;
         direction: ltr !important;
      }

      [dir="rtl"] .iti {
         direction: ltr;
      }
   </style>
   <script>
      const input = document.querySelector("#phone");
      const iti = window.intlTelInput(input, {
         loadUtils: () => import("https://cdn.jsdelivr.net/npm/intl-tel-input@25.10.0/build/js/utils.js"),
         initialCountry: "sa",
         preferredCountries: ["sa", "ae", "kw", "bh", "om", "qa"],
         onlyCountries: ["sa", "ae", "kw", "bh", "om", "qa"],
         separateDialCode: true,
         i18n: {
            sa: "السعودية",
            ae: "الإمارات",
            kw: "الكويت",
            bh: "البحرين",
            om: "عُمان",
            qa: "قطر",
            selectedCountryAriaLabel: "تغيير الدولة، الدولة المختارة ${countryName} (${dialCode})",
            noCountrySelected: "اختر الدولة",
            countryListAriaLabel: "قائمة الدول",
            searchPlaceholder: "بحث",
            zeroSearchResults: "لا توجد نتائج",
            oneSearchResult: "نتيجة واحدة",
            multipleSearchResults: "${count} نتائج"
         },
         hiddenInput: (telInputName) => ({
            phone: "phone_full",
            country: "country_code"
         }),
      });

      // تفعيل زر إرسال كود التفعيل
      document.getElementById('send-code-btn').addEventListener('click', function () {
         const phone = iti.getNumber();
         // طباعة الرقم النهائي في الكونسول قبل الإرسال
         console.log('رقم الجوال النهائي المرسل:', phone);
         const smsStatus = document.getElementById('sms-status');
         if (!iti.isValidNumber()) {
            smsStatus.textContent = 'رقم غير صحيح';
            smsStatus.classList.remove('text-success');
            smsStatus.classList.add('text-danger');
            return;
         }
         smsStatus.textContent = 'جاري الإرسال...';
         smsStatus.classList.remove('text-danger');
         smsStatus.classList.add('text-success');
         fetch('/phone/send-code', {
            method: 'POST',
            headers: {
               'Content-Type': 'application/json',
               'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ phone })
         })
            .then(res => res.json())
            .then(res => {
               if (res.success) {
                  smsStatus.textContent = 'تم إرسال الكود بنجاح';
                  const codeSection = document.getElementById('code-section');
                  if (codeSection) codeSection.style.display = '';
               } else {
                  smsStatus.textContent = res.message || 'حدث خطأ';
                  smsStatus.classList.add('text-danger');
               }
            })
            .catch(() => {
               smsStatus.textContent = 'حدث خطأ أثناء الإرسال';
               smsStatus.classList.add('text-danger');
            });
      });
   </script>
</body>

</html>