@extends('main.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">ساعدنا نعرف رغبتك</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('update_suggestions') }}">
                        @csrf
                        @method('PATCH')

                        <div class="form-group row">
                            <label for="city" class="col-md-2 col-form-label text-md-right">مدينة السكن</label>
                        
                            <div class="col-md-10">
                                <input type="text" id="city"  class="form-control @error('city') is-invalid @enderror" name="city" value="{{ auth()->user()->city }}" required autocomplete="city">
                        
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
                                <input type="date" id="birth_date"  class="form-control @error('birth_date') is-invalid @enderror" name="birth_date" value="{{ auth()->user()->birth_date }}" required autocomplete="birth_date">
                        
                                @error('birth_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        {{-- <div class="form-group row">
                            <label for="mobile" class="col-md-2 col-form-label text-md-right">الجوال</label>
                        
                            <div class="col-md-10">
                                <input type="text" name="mobile" id="mobile" class="form-control @error('mobile') is-invalid @enderror"  value="{{ auth()->user()->mobile }}" required autocomplete="mobile">
                        
                                @error('mobile')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> --}}

                        <div class="form-group row">
                            <label for="max_budget" class="col-md-2 col-form-label text-md-right">الميزانية القصوى للإستثمار</label>
                        
                            <div class="col-md-10">
                                <input type="number" id="max_budget"  class="form-control @error('max_budget') is-invalid @enderror" name="max_budget" value="{{ auth()->user()->max_budget }}" required autocomplete="max_budget">
                        
                                @error('max_budget')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    إرسال
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection