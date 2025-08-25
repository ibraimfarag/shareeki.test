@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">


                <div class="card">
                    <div class="card-header"> <h5 class="text-center">إسم الموقع</h5> </div>

                    <div class="card-body">

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('settings.update', $settings->id) }}">
                            @csrf
                            @method('PATCH')

                            <div class="form-group row">
                                <label for="name" class="col-md-2 col-form-label text-md-right">إسم الموقع</label>

                                <div class="col-md-10">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $settings->name }}" required autocomplete="name" autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary">
                                    تغيير الإسم
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">

                    <div class="card-header"><h5 class="text-center">بيانات التواصل</h5></div>

                    <div class="card-body">

                        @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('settings.update', $settings->id) }}">
                        @csrf
                        @method('PATCH')


                        <div class="form-group row">
                            <label for="telephone" class="col-md-2 col-form-label text-md-right">رقم التليفون</label>

                            <div class="col-md-10">
                                <input id="telephone" type="text" class="form-control @error('telephone') is-invalid @enderror" name="telephone" value="{{ $settings->telephone }}" required autocomplete="telephone">

                                @error('telephone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    


                        <div class="form-group row">
                            <label for="email" class="col-md-2 col-form-label text-md-right">البريد الإلكتروني</label>

                            <div class="col-md-10">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $settings->email }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                تعديل بيانات التواصل
                            </button>
                        </form>

                        </div>

                    </div>

                </div>

                {{--<div class="card">
                    <div class="card-header"><h5 class="text-center">أيقونات الموقع</h5></div>

                    <div class="card-body">

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="container">
                            <div class="row justify-content-center">
                                @foreach ($icons as $icon)
                                    <div class="col-md-3" id="the_icon_{{ $icon->id }}">
                                        <img src="{{ $icon->icon_path }}" style="width:60px;height:60px;margin: 16px 97px;background-color: black;" class="rounded-circle" onclick="document.getElementById('image_{!! $icon->id !!}').click()" alt="Cinque Terre">
                                        <h5 class="text-center">{{ $icon->name }}</h5>
                                        <input onchange="changeIcon({{ $icon->id }})" style="display: none;"  id="image_{{ $icon->id }}" type="file" name="image">
                                        <input type="hidden" id="icon_id_{{ $icon->id }}" value="{{ $icon->id }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>--}}

                <div class="card">
                    <div class="card-header"><h5 class="text-center">عن الموقع وبيانات التواصل الإجتماعي</h5></div>

                    <div class="card-body">

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('settings.update', $settings->id) }}">
                            @csrf
                            @method('PATCH')

                            <div class="form-group row">
                                <label for="about" class="col-md-2 col-form-label text-md-right">عن الموقع</label>
    
                                <div class="col-md-10">
                                    <textarea name="about" id="about" class="form-control @error('about') is-invalid @enderror textarea" cols="30" rows="10" required autocompleted="about">{{ $settings->about }}</textarea>
    
                                    @error('about')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="facebook" class="col-md-2 col-form-label text-md-right">{{ __('Facebook') }}</label>

                                <div class="col-md-10">
                                    <input id="facebook" type="url" class="form-control @error('facebook') is-invalid @enderror" name="facebook" value="{{ $settings->facebook }}" required autocomplete="facebook" autofocus>

                                    @error('facebook')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="googleplus" class="col-md-2 col-form-label text-md-right">{{ __('googleplus') }}</label>

                                <div class="col-md-10">
                                    <input id="googleplus" type="url" class="form-control @error('googleplus') is-invalid @enderror" name="googleplus" value="{{ $settings->googleplus }}" required autocomplete="googleplus" autofocus>

                                    @error('googleplus')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="youtube" class="col-md-2 col-form-label text-md-right">{{ __('youtube') }}</label>

                                <div class="col-md-10">
                                    <input id="youtube" type="url" class="form-control @error('youtube') is-invalid @enderror" name="youtube" value="{{ $settings->youtube }}" required autocomplete="youtube" autofocus>

                                    @error('youtube')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="twitter" class="col-md-2 col-form-label text-md-right">{{ __('twitter') }}</label>

                                <div class="col-md-10">
                                    <input id="twitter" type="url" class="form-control @error('twitter') is-invalid @enderror" name="twitter" value="{{ $settings->twitter }}" required autocomplete="twitter" autofocus>

                                    @error('twitter')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="telegram" class="col-md-2 col-form-label text-md-right">{{ __('telegram') }}</label>

                                <div class="col-md-10">
                                    <input id="telegram" type="url" class="form-control @error('telegram') is-invalid @enderror" name="telegram" value="{{ $settings->telegram }}" required autocomplete="telegram" autofocus>

                                    @error('telegram')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="whatsapp" class="col-md-2 col-form-label text-md-right">{{ __('whatsapp') }}</label>

                                <div class="col-md-10">
                                    <input id="whatsapp" type="url" class="form-control @error('whatsapp') is-invalid @enderror" name="whatsapp" value="{{ $settings->whatsapp }}" required autocomplete="whatsapp" autofocus>

                                    @error('whatsapp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="snapchat" class="col-md-2 col-form-label text-md-right">{{ __('snapchat') }}</label>

                                <div class="col-md-10">
                                    <input id="snapchat" type="url" class="form-control @error('snapchat') is-invalid @enderror" name="snapchat" value="{{ $settings->snapchat }}" required autocomplete="snapchat" autofocus>

                                    @error('snapchat')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="linkedin" class="col-md-2 col-form-label text-md-right">{{ __('linkedin') }}</label>

                                <div class="col-md-10">
                                    <input id="linkedin" type="url" class="form-control @error('linkedin') is-invalid @enderror" name="linkedin" value="{{ $settings->linkedin }}" required autocomplete="linkedin" autofocus>

                                    @error('linkedin')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary">
                                    تعديل بيانات عن الموقع وبيانات مواقع التواصل الإجتماعي
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{--<div class="card">
                    <div class="card-header"><h5 class="text-center">روابط التطبيق على متاجر الجوال</h5></div>

                    <div class="card-body">

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('settings.update', $settings->id) }}">
                            @csrf
                            @method('PATCH')

                            <div class="form-group row">
                                <label for="play_store" class="col-md-2 col-form-label text-md-right">{{ __('play store') }}</label>

                                <div class="col-md-10">
                                    <input id="play_store" type="url" class="form-control @error('play_store') is-invalid @enderror" name="play_store" value="{{ $settings->play_store }}" required autocomplete="play_store" autofocus>

                                    @error('play_store')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="app_store" class="col-md-2 col-form-label text-md-right">{{ __('app store') }}</label>

                                <div class="col-md-10">
                                    <input id="app_store" type="url" class="form-control @error('app_store') is-invalid @enderror" name="app_store" value="{{ $settings->app_store }}" required autocomplete="app_store" autofocus>

                                    @error('app_store')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="microsoft_store" class="col-md-2 col-form-label text-md-right">{{ __('microsoft store') }}</label>

                                <div class="col-md-10">
                                    <input id="microsoft_store" type="url" class="form-control @error('microsoft_store') is-invalid @enderror" name="microsoft_store" value="{{ $settings->microsoft_store }}" required autocomplete="microsoft_store" autofocus>

                                    @error('microsoft_store')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary">
                                    تعديل روابط التطبيق على متاجر الجوال
                                </button>
                            </div>
                        </form>
                    </div>
                </div>--}}


                <div class="card">

                    <div class="card-header"><h5 class="text-center">بيانات اخرى</h5></div>

                    <div class="card-body">

                        @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('settings.update', $settings->id) }}">
                        @csrf
                        @method('PATCH')


                        <div class="form-group row">
                            <label for="commission_percentage" class="col-md-2 col-form-label text-md-right">نسيبة العمولة</label>

                            <div class="col-md-10">
                                <input id="commission_percentage" type="text" class="form-control @error('commission_percentage') is-invalid @enderror" name="commission_percentage" value="{{ $settings->commission_percentage }}" required autocomplete="commission_percentage">

                                @error('commission_percentage')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                تعديل بيانات اخرى
                            </button>
                        </form>

                        </div>

                    </div>

                </div>

       
        </div>
    </div>
</div>
@endsection


@section('footer')
    <script>


        window.form_data = new FormData();

       
        function changeIcon(id){
            let file_data = $(`#image_${id}`).prop('files')[0];
            form_data.append('file_data', file_data);
            form_data.append('icon_id', $(`#icon_id_${id}`).val());
            

            axios.post('../../../admin/icons/settings', form_data)
            .then((data) => {
                location.reload();
                                      
            }).catch((error) => {

            
            
            });
        
        }
    </script>
   
@endsection