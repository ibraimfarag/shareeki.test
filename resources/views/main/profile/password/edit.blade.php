@extends('main.layouts.app')

@section('content')
<section class="form-block d-flex align-items-center my-5">
         <div class="container">
            <div class="row justify-content-center">
               <div class="col-lg-6">
                  <div class="border border-radius-medium p-5 mt-5">
                     <h3 class="h3 text-dark-heading mb-3">
                        تعديل كلمة المرور
                     </h3>
                     <div class="f">
                        <form class="needs-validation" novalidate>
                        <form method="POST" novalidate action="{{ route('update_change_password') }}">
                        @csrf
                        @method('PATCH')

                           <div class="form-floating mb-3">
                              <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                              <label for="floatingInput">البريد الالكتروني</label>
                            
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                           </div>
                           <div class="form-floating mb-3">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                <label for="floatingPassword"> ادخل كلمة المرور القديمة</label>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                           </div>

                           <div class="form-floating mb-3">
                              <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                              <label for="floatingPassword"> ادخل كلمة المرور الجديدة</label>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                           </div>

                           <div class="form-floating mb-3">
                              <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                              <label for="floatingPassword">تأكيد كلمة المرور</label>
                              
                           <button type="submit" class="btn main-btn blue-btn p-3 rounded w-100 mb-2">
                              حفظ التغيرات
                           </button>
                        </form>

                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
@endsection