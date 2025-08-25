@extends('admin.layouts.app')

@section('head')
    <style>
        a { text-decoration: none; color: #f2f2f2; }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">


        <div class="col-md-6 col-xl-3">
            <a href="{{ route('settings.edit') }}">
                <div class="card">
                    <div class="card-body">
                    <div class="mini-stat">
                        <span class="mini-stat-icon bg-warning float-start">
                            <i class="mdi mdi-cube-outline"></i></span>
                        <div class="mini-stat-info text-end">
                            <h5 class="text-center"> الإعدادت الأساسية </h5>
                        </div>
                    </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-xl-3">
            <a href="{{ route('pages.edit', 'terms') }}">
                <div class="card">
                    <div class="card-body">
                    <div class="mini-stat">
                        <span class="mini-stat-icon bg-warning float-start">
                            <i class="mdi mdi-cube-outline"></i></span>
                        <div class="mini-stat-info text-end">
                            <h5 class="text-center"> صفحة الشروط </h5>
                        </div>
                    </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-xl-3">
            <a href="{{ route('pages.edit', 'faqs') }}">
                <div class="card">
                    <div class="card-body">
                    <div class="mini-stat">
                        <span class="mini-stat-icon bg-warning float-start">
                            <i class="mdi mdi-cube-outline"></i></span>
                        <div class="mini-stat-info text-end">
                            <h5 class="text-center"> صفحة الإرشادات </h5>
                        </div>
                    </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-xl-3">
            <a href="{{ route('pages.edit', 'agreements') }}">
                <div class="card">
                    <div class="card-body">
                    <div class="mini-stat">
                        <span class="mini-stat-icon bg-warning float-start">
                            <i class="mdi mdi-cube-outline"></i></span>
                        <div class="mini-stat-info text-end">
                            <h5 class="text-center"> صفحة التعهد </h5>
                        </div>
                    </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-xl-3">
            <a href="{{ route('pages.edit', 'about') }}">
                <div class="card">
                    <div class="card-body">
                    <div class="mini-stat">
                        <span class="mini-stat-icon bg-warning float-start">
                            <i class="mdi mdi-cube-outline"></i></span>
                        <div class="mini-stat-info text-end">
                            <h5 class="text-center"> صفحة من نحن </h5>
                        </div>
                    </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-xl-3">
            <a href="{{ route('pages.edit', 'commission') }}">
                <div class="card">
                    <div class="card-body">
                    <div class="mini-stat">
                        <span class="mini-stat-icon bg-warning float-start">
                            <i class="mdi mdi-cube-outline"></i></span>
                        <div class="mini-stat-info text-end">
                            <h5 class="text-center"> صفحة دفع العمولة </h5>
                        </div>
                    </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-xl-3">
            <a href="{{ route('pages.edit', 'bank_account') }}">
                <div class="card">
                    <div class="card-body">
                    <div class="mini-stat">
                        <span class="mini-stat-icon bg-warning float-start">
                            <i class="mdi mdi-cube-outline"></i></span>
                        <div class="mini-stat-info text-end">
                            <h5 class="text-center"> صفحة الحساب البنكي </h5>
                        </div>
                    </div>
                    </div>
                </div>
            </a>
        </div>

            

    </div>
</div>
@endsection