@extends('main.layouts.app')

@section('header')
    <style>
        body{
            overflow-x: hidden;
        }
    </style>
@endsection

@section('content')

    <img style="margin: 0 46%;width:100px;" src="{{ url('main/images/success.gif') }}" alt="" srcset="">

    <h1 class="text-center">كل التوفيق</h1>

    <div class="row text-center mt-2 mb-2" style="margin: 0 25%;">
        <div class="btn btn-primary col-md-12"><a href="{{ route('the_posts.show', $slug) }}" class="text-white">مشاهدة الإعلان</a></div>
    </div>

@endsection