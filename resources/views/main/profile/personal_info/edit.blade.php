@extends('main.layouts.app')

@section('content')
<section class="edite-profile mt-5">
         <div class="container">
            <div class="row justify-content-center">
               <div class="col-lg-6">
                  <div class="border border-radius-medium p-5 mt-5">
                     <h3 class="h3 text-dark-heading mb-3">
                        تعديل المعلومات الشخصية
                     </h3>
                     <div class="f">
                        <form method="POST" action="{{ route('update_personal_info') }}" novalidate enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                           <div class="form-floating mb-3">
                              <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ auth()->user()->name }}" required autocomplete="name" autofocus>
                              <label for="floatingInput">الاسم</label>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                              
                              
                           </div>
                           <div class="form-floating mb-3">
                              <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ auth()->user()->email }}" required autocomplete="email">
                                <label for="floatingInput">البريد الالكتروني</label>
                                @error('email')
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