@extends('admin.layouts.app')

@section('content')
    
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title text-center">تعديل صفحة {{ $page->title }}</h2>
                    {{ Form::open(array('method' => 'PATCH','files' => true,'class' => 'card','url' =>'admin/pages/'.$page->name )) }}
                        <div class="card-body">
                            <div class="row">
                            <div class="form-group col-md-12">
                                <label class="form-label">إسم الصفحة</label>
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ $page->title }}" required autocomplete="title" autofocus>
                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                                <div class="form-group col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">محتوى الصفحة</label>
                                        <textarea id="editor1" style="width:100%;" type="text" placeholder="محتوى الصفحة" class="form-control" name="body">{{ $page->body }}</textarea>
                                        @error('body')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-4 btn-list text-center col-md-12">
                                    <button type="submit" class="btn btn-primary col-md-4">تعديل</button>
                                </div>

                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection