@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h2 class="card-header text-center">التعديل على الدولة {{ $country->name }}</h2>

                <div class="card-body">
                    <form method="POST" action="{{ route('countries.update' , $country->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        

                        <div class="form-group row">
                            <label for="arabic" class="col-md-2 col-form-label text-md-right">الإسم بالعربية</label>

                            <div class="col-md-10">
                                <input id="arabic" type="text" class="form-control @error('arabic') is-invalid @enderror" name="arabic" value="{{ $country->name }}" required autocomplete="arabic" autofocus>

                                @error('arabic')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="english" class="col-md-2 col-form-label text-md-right">الإسم بالإنجليزية</label>

                            <div class="col-md-10">
                                <input id="english" type="text" class="form-control @error('english') is-invalid @enderror" name="english" value="{{ $country->english }}" required autocomplete="english" autofocus>

                                @error('english')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                     
                        <div class="form-group mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success">
                                    تعديل
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