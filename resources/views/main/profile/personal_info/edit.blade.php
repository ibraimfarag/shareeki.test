@extends('main.layouts.app')

@section('header')
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.10.0/build/css/intlTelInput.css">


@endsection

@section('content')



   <section class="edite-profile mt-5">

      <div class="container">
         <div class="row justify-content-center">
            <div class="col-lg-6">
               <div class="border border-radius-medium p-5 mt-5">
                  <h3 class="h3 text-dark-heading mb-3">
                     تعديل المعلومات الشخصية
                  </h3>
                  @if ($errors->any())
                     <div class="alert alert-danger">
                        <ul class="mb-0">
                           @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                           @endforeach
                        </ul>
                     </div>
                  @endif
                  <div class="f">
                     <form method="POST" action="{{ route('update_personal_info') }}" novalidate
                        enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="form-floating mb-3">
                           <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                              name="name" value="{{ auth()->user()->name }}" required autocomplete="name" autofocus>
                           <label for="floatingInput">الاسم</label>
                           @error('name')
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                           @enderror
                        </div>
                        <div class="form-floating mb-3">
                           <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                              name="email" value="{{ auth()->user()->email }}" required autocomplete="email">
                           <label for="floatingInput">البريد الالكتروني</label>
                           @error('email')
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
                                 class="form-control @error('phone') is-invalid @enderror"
                                 value="{{ old('phone', auth()->user()->phone) }}" required autocomplete="tel" dir="ltr"
                                 style="text-align:left;">
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

                        <button type="submit" class="btn main-btn blue-btn p-3 rounded w-100 mb-2">
                           تعديل
                        </button>
                     </form>

                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>



@endsection


@section('footer')



   <style>
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
      .iti .iti__flag-container {
         right: auto;
         left: 0;
      }

      .iti input {
         padding-right: 0 !important;
         padding-left: 82px !important;
         direction: ltr !important;
      }

      .iti {
         direction: ltr;
      }
   </style>

   <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.10.0/build/js/intlTelInput.min.js"></script>


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
      // intl-tel-input custom style for RTL
      document.addEventListener('DOMContentLoaded', function () {
         function updatePhoneWithDialCode() {
            let phone = iti.getNumber('E.164').replace(/^\+/, '');
            input.value = phone;
         }
         input.addEventListener('countrychange', updatePhoneWithDialCode);
         input.addEventListener('blur', updatePhoneWithDialCode);
         const form = document.querySelector('form[action*="update_personal_info"]');
         if (form) {
            form.addEventListener('submit', function (e) {
               if (input && iti) {
                  let phone = iti.getNumber('E.164').replace(/^\+/, '');
                  input.value = phone;
                  // console.log('قيمة الهاتف النهائية المرسلة في الفورم:', phone);
               }
            });
         }
         document.getElementById('send-code-btn').addEventListener('click', function () {
            const phone = iti.getNumber();
            // console.log('رقم الجوال النهائي المرسل:', phone);
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
      });
   </script>
@endsection