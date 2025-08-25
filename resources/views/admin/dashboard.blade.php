@extends('admin.layouts.app')
@section('content')

  <div class="container-fluid">
    <div class="row" style="margin-top: 20px;">

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                <div class="mini-stat">
                    <span class="mini-stat-icon bg-primary float-start">
                      <i class="mdi mdi-buffer"></i></span>
                    <div class="mini-stat-info text-end">
                        <span class="counter text-primary">{{ $lastLikes }}</span>
                        عدد الإعجابات الجدد
                    </div>
                </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                <div class="mini-stat">
                    <span class="mini-stat-icon bg-primary float-start">
                      <i class="mdi mdi-buffer"></i></span>
                    <div class="mini-stat-info text-end">
                        <span class="counter text-primary">{{ $likes }}</span>
                        عدد الإعجابات 
                    </div>
                </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                <div class="mini-stat">
                    <span class="mini-stat-icon bg-primary float-start">
                      <i class="mdi mdi-buffer"></i></span>
                    <div class="mini-stat-info text-end">
                        <span class="counter text-primary">{{ $lastReports }}</span>
                        عدد البلاغات الجديدة 
                    </div>
                </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                <div class="mini-stat">
                    <span class="mini-stat-icon bg-primary float-start">
                      <i class="mdi mdi-buffer"></i></span>
                    <div class="mini-stat-info text-end">
                        <span class="counter text-primary">{{ $lastUsers }}</span>
                        عدد المستخدمين الجدد
                    </div>
                </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                <div class="mini-stat">
                    <span class="mini-stat-icon bg-primary float-start">
                      <i class="mdi mdi-buffer"></i></span>
                    <div class="mini-stat-info text-end">
                        <span class="counter text-primary">{{ $users }}</span>
                        عدد المستخدمين 
                    </div>
                </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                <div class="mini-stat">
                    <span class="mini-stat-icon bg-primary float-start">
                      <i class="mdi mdi-buffer"></i></span>
                    <div class="mini-stat-info text-end">
                        <span class="counter text-primary">{{ $lastPosts }}</span>
                        عدد الإعلانات الجديدة
                    </div>
                </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                <div class="mini-stat">
                    <span class="mini-stat-icon bg-primary float-start">
                      <i class="mdi mdi-buffer"></i></span>
                    <div class="mini-stat-info text-end">
                        <span class="counter text-primary">{{ $posts }}</span>
                        عدد الإعلانات
                    </div>
                </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                <div class="mini-stat">
                    <span class="mini-stat-icon bg-primary float-start">
                      <i class="mdi mdi-buffer"></i></span>
                    <div class="mini-stat-info text-end">
                        <span class="counter text-primary">{{ $totalPrices }}</span>
                        إجمالي المبالغ لمن لديه إعجاب
                    </div>
                </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                <div class="mini-stat">
                    <span class="mini-stat-icon bg-primary float-start">
                      <i class="mdi mdi-buffer"></i></span>
                    <div class="mini-stat-info text-end">
                        <span class="counter text-primary">{{ $reports }}</span>
                        الإعلانات المحظورة
                    </div>
                </div>
                </div>
            </div>
        </div>

    </div>
  </div>

@endsection
@section('footer')

@endsection