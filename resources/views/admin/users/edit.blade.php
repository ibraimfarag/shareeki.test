@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h4 class="text-center">تعديل بيانات المستخدم</h4></div>

                <div class="card-body">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.update', $user->name) }}">
                        @method('PATCH')
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">الإسم</label>

                            <div class="col-md-10">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="city" class="col-md-2 col-form-label text-md-right">مدينة السكن</label>
                        
                            <div class="col-md-10">
                                <input type="text" id="city"  class="form-control @error('city') is-invalid @enderror" name="city" value="{{ $user->city }}" required autocomplete="city">
                        
                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="birth_date" class="col-md-2 col-form-label text-md-right">تاريخ الميلاد</label>
                        
                            <div class="col-md-10">
                                <input type="date" id="birth_date"  class="form-control @error('birth_date') is-invalid @enderror" name="birth_date" value="{{ $user->birth_date }}" required autocomplete="birth_date">
                        
                                @error('birth_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="mobile" class="col-md-2 col-form-label text-md-right">الجوال</label>
                        
                            <div class="col-md-10">
                                <input type="text" name="mobile" id="mobile" class="form-control @error('mobile') is-invalid @enderror"  value="{{ $user->mobile }}" required autocomplete="mobile">
                        
                                @error('mobile')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="email" class="col-md-2 col-form-label text-md-right">البريد الإلكترونى</label>
                        
                            <div class="col-md-10">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" required autocomplete="email">
                        
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="max_budget" class="col-md-2 col-form-label text-md-right">الميزانية القصوى للإستثمار</label>
                        
                            <div class="col-md-10">
                                <input type="number" id="max_budget"  class="form-control @error('max_budget') is-invalid @enderror" name="max_budget" value="{{ $user->max_budget }}" required autocomplete="max_budget">
                        
                                @error('max_budget')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
    
                        <div class="form-group row">
                            <label for="password" class="col-md-2 col-form-label text-md-right">كلمة المرور</label>

                            <div class="col-md-10">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                      
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                تعديل بيانات المستخدم
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection