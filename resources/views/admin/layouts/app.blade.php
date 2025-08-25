<!doctype html>
<html lang="en" style="direction: rtl;">
    <head>
        <meta charset="utf-8">
        <title>{{ $settings->name }} - لوحة التحكم</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description">
        <meta content="Shareeki" name="author">
        <!-- App favicon -->
        <link rel="icon" href="{{ asset('admin/images/'.$settings->header_logo) }}" type="image/x-icon">

        <!-- C3 Chart css -->
        <link href="{{ asset('admin/assets/libs/c3/c3.min.css') }}" rel="stylesheet" type="text/css">

        <!-- DataTables -->
        <link href="{{ asset('admin/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }} " rel="stylesheet" type="text/css">
        <link href="{{ asset('admin/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">

        <!-- Responsive datatable examples -->
        <link href="{{ asset('admin/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">

        <!-- select2 -->
        <link href="{{ asset('admin/assets/libs/select2/css/select2.min.css') }} " rel="stylesheet" type="text/css">
        <link href="{{ asset('admin/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }} " rel="stylesheet">
        <link href="{{ asset('admin/assets/libs/spectrum-colorpicker2/spectrum.min.css') }} " rel="stylesheet" type="text/css">
        <link href="{{ asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css') }} " rel="stylesheet">

         <!-- Bootstrap Rating css -->
        <link href="{{ asset('admin/assets/libs/bootstrap-rating/bootstrap-rating.css') }}" rel="stylesheet" type="text/css">

        <!-- Bootstrap Css -->
        <link href="{{ asset('admin/assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet" type="text/css">
        <!-- bootstrap wysihtml5 - text editor -->
        <link rel="stylesheet" href="{{asset('admin/assets/libs/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
        <!-- Icons Css -->
        <link href="{{ asset('admin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
        <!-- App Css-->
        <link href="{{ asset('admin/assets/css/appp-rtl.min.css') }}" rel="stylesheet" type="text/css">
        <script src="{{ asset('admin/assets/libs/jquery/jquery.min.js') }}"></script>
        @yield('header')
    </head>
    <style>
        .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(52, 58, 64, 0.5);
            content: "\f105" !important;
            font-family: 'Font Awesome 5 Free';
            font-weight: 700;
        }
        .form-group{
            margin-bottom:2%;
        }
        .mb-0{
            text-align:center;
        }

    </style>

    <body data-sidebar="dark">
        <!-- Loader -->
            <div id="preloader"><div id="status"><div class="spinner"></div></div></div>

        <!-- Begin page -->
        <div id="layout-wrapper">

            <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box">
                            <a href="{{ asset('admin/dashboard') }}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{ asset('admin/images/'.$settings->minidashboard_logo) }}" onerror="this.src='{{asset('admin/images/default.png')}}'" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('admin/images/'.$settings->dashboard_logo) }}" onerror="this.src='{{asset('admin/images/default.png')}}'" alt="" height="17">
                                </span>
                            </a>

                            <a href="{{ asset('admin/dashboard') }}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{ asset('admin/images/'.$settings->minidashboard_logo) }}" onerror="this.src='{{asset('admin/images/default.png')}}'" alt="" height="22">
                                </span>name: 
                                <span class="logo-lg">
                                    <img src="{{ asset('admin/images/'.$settings->dashboard_logo) }}" onerror="this.src='{{asset('admin/images/default.png')}}'" alt="" height="18">
                                </span>
                            </a>
                        </div>

                        <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                            <i class="mdi mdi-menu"></i>
                        </button>

                        <div class="dropdown d-inline-block me-2">
                              <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                  <i class="ion ion-md-notifications"></i>
                                  <!--<span  class="badge bg-danger rounded-pill">{{--{{$adminContact->count()}}--}}</span>-->
                              </button>
                              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown" style="">
                                  <div class="p-3">
                                      <div class="row align-items-center">
                                          <div class="col">
                                              <h5 class="m-0 font-size-16"> الإشعارات </h5>
                                          </div>
                                      </div>
                                  </div>
                                  <div data-simplebar="init" style="max-height: 230px;"><div class="simplebar-wrapper" style="margin: 0px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: 0px; bottom: 0px;"><div class="simplebar-content-wrapper" style="height: auto; overflow: hidden;"><div class="simplebar-content" style="padding: 0px;">

                                  @foreach($adminContact as $c)
                                      <a href="{{url('admin/contacts/'.$c->id)}}" class="text-reset notification-item">
                                          <div class="d-flex">
                                              <div class="avatar-xs me-3">
                                                  <span class="avatar-title bg-success rounded-circle font-size-16">
                                                      <i class="mdi mdi-bell"></i>
                                                  </span>
                                              </div>
                                              <div class="flex-1">
                                                  <h6 class="mt-0 font-size-15 mb-1">{{$c->name}}</h6>

                                                  <div class="font-size-12 text-muted">
                                                      <p class="mb-1"> {{ Str::limit($c->body, 50) }} </p>
                                                  </div>
                                                  <small><i class="fa fa-clock-o"></i>{{ $c->created_at->diffForHumans() }}</small>
                                              </div>
                                          </div>
                                      </a>
                                  @endforeach

                                  </div></div></div></div><div class="simplebar-placeholder" style="width: 0px; height: 0px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: hidden;"><div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: none;"></div></div></div>
                                  <div class="p-2 border-top">
                                      <div class="d-grid">
                                          <a class="btn btn-sm btn-link font-size-14  text-center" href="{{ asset('admin/contacts') }}">
                                          مشاهدة كل الرسائل
                                          </a>
                                      </div>
                                  </div>
                              </div>
                        </div>

                        <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                            <a class="btn btn-icon btn-circle btn-light" href="{{ asset('/') }}" target="_blank" title="Shareeki">
                                <i class="mdi mdi-earth-arrow-right"></i>
                            </a>
                        </button>

                    </div>

                    <div class="d-flex">
                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle header-profile-user" src="{{asset('admin/images/'.auth()->guard('admin')->user()->image)}}"  onerror="this.src='{{asset('admin/images/default.png')}}'" alt="{{auth()->guard('admin')->user()->username}}">
                            </button>
                            <div class="dropdown-menu dropdown-menu-end ">
                                <a class="dropdown-item" href="{{ route('admins.edit', Auth::user()->user_name) }}"><i class="mdi mdi-account-circle font-size-17 text-muted align-middle me-1"></i> الصفحة الشخصية</a>
							    <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="{{asset('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" ><i class="mdi mdi-power font-size-17 text-muted align-middle me-1 text-danger"></i>تسجيل خروج</a>
                                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li>
                                <a style="color: #fafafa !important" href="{{ asset('admin\dashboard') }}" class="waves-effect">
                                    <i class="mdi mdi-view-dashboard"></i>
                                    <span>لوحة المؤشرات</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="mdi mdi-view-dashboard"></i>
                                    <span>القطاعات الإستثمارية</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{asset('admin/categories')}}"> كل القطاعات الإستثمارية </a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="mdi mdi-view-dashboard"></i>
                                    <span>الإعلانات</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{asset('admin/posts')}}"> كل الإعلانات  </a></li>
                                </ul>
                            </li>

                             <li>
                                <a href="{{asset('admin/posts/type_enum/featured')}}" class=" waves-effect">
                                    <i class="mdi mdi-view-dashboard"></i>
                                    <span>الاعلانات المميزة</span>
                                </a>
                               
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="mdi mdi-view-dashboard"></i>
                                    <span>الإعلانات الأكثر إعجاب</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{asset('admin/posts/type_enum/most_liked')}}"> كل الإعلانات الأكثر إعجاب  </a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="mdi mdi-view-dashboard"></i>
                                    <span> الإعلانات الأكثر عدم إعجاب </span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{asset('admin/posts/type_enum/most_disliked')}}"> كل الإعلانات الأكثر عدم إعجاب  </a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="mdi mdi-view-dashboard"></i>
                                    <span> الإعلانات الأكثر بلاغا </span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{asset('admin/posts/type_enum/most_reported')}}"> كل الإعلانات الأكثر بلاغا  </a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="mdi mdi-view-dashboard"></i>
                                    <span> الإعلانات المحظورة </span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{asset('admin/posts/type_enum/blacklisted')}}"> كل الإعلانات المحظورة  </a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="mdi mdi-view-dashboard"></i>
                                    <span> رسائل التواصل </span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{asset('admin/contacts')}}"> كل رسائل التواصل  </a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="mdi mdi-view-dashboard"></i>
                                    <span> المستخدمين </span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{asset('admin/users')}}"> كل المستخدمين  </a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="mdi mdi-view-dashboard"></i>
                                    <span> الدول </span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{asset('admin/countries')}}"> كل الدول  </a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="mdi mdi-view-dashboard"></i>
                                    <span> المشرفين </span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{asset('admin/admins')}}"> كل المشرفين  </a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="mdi mdi-view-dashboard"></i>
                                    <span> الإعدادات </span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{asset('admin/the_settings')}}"> إعدادات الموقع  </a></li>
                               <li><a href="{{ route('admin.ad-types.index') }}"> إعدادات الإعلانات المميزة </a></li>
    <li><a href="{{ route('admin.pricing-rules.index') }}"> قواعد التسعير </a></li>

                                </ul>
                            </li>

                        </ul>
                    </div>
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->

            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">

                        @if(session()->has('error') )
                          <div style="text-align: center;font-size: large;" class="alert alert-icon text-center alert-danger" role="alert">
                            <i class="fa fa-frown-o mr-2" aria-hidden="true"></i> {{ session('error')}}
                          </div>
                        @endif

                        @if(session()->has('success') )
                          <div style="text-align:center;background-color:green;" class="alert alert-icon alert-success" role="alert">
                            <i class="fa fa-check-circle-o mr-2" aria-hidden="true"></i> {{ session('success')}}
                          </div>
                        @endif

                        @yield('content')
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> © shareeki.
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
                                    Crafted with <i class="mdi mdi-heart text-danger"></i> by shareeki
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
            <!-- end main content-->
        </div>
        <!-- END layout-wrapper -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->

        <script src="{{ asset('admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('admin/assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('admin/assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('admin/assets/libs/node-waves/waves.min.js') }}"></script>

        <!-- Required datatable js -->
        <script src="{{ asset('admin/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('admin/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <!-- Buttons examples -->
        <script src="{{ asset('admin/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('admin/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('admin/assets/libs/jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('admin/assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
        <script src="{{ asset('admin/assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
        <script src="{{ asset('admin/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('admin/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('admin/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
        <!-- Responsive examples -->
        <script src="{{ asset('admin/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('admin/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

        <!-- Datatable init js -->
        <script src="{{ asset('admin/assets/js/pages/datatables.init.js') }}"></script>

        <!-- Bootstrap rating js -->
        <script src="{{ asset('admin/assets/libs/bootstrap-rating/bootstrap-rating.min.js') }}"></script>
        <script src="{{ asset('admin/assets/js/pages/rating-init.js') }}"></script>

        <!-- select2 js -->
        <script src="{{ asset('admin/assets/libs/select2/js/select2.min.js') }} "></script>
        <script src="{{ asset('admin/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }} "></script>
        <script src="{{ asset('admin/assets/libs/spectrum-colorpicker2/spectrum.min.js') }} "></script>
        <script src="{{ asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js') }} "></script>
        <script src="{{ asset('admin/assets/libs/admin-resources/bootstrap-filestyle/bootstrap-filestyle.min.js') }} "></script>
        <script src="{{ asset('admin/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }} "></script>

        <script src="{{ asset('admin/assets/js/pages/form-advanced.init.js') }} "></script>

        <!-- Bootstrap WYSIHTML5 -->
        <script src="{{asset('admin/assets/libs/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>

         <!-- CK Editor -->
        <script src="{{asset('admin/assets/libs/ckeditor/ckeditor.js')}}"></script>

        <!-- Peity chart-->
        <script src="{{ asset('admin/assets/libs/peity/jquery.peity.min.js') }} "></script>
        <!-- google maps api -->

        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <!--Morris Chart-->
        <script src="{{ asset('admin/assets/libs/morris.js/morris.min.js') }} "></script>
        <script src="{{ asset('admin/assets/libs/raphael/raphael.min.js') }} "></script>
        <script src="{{ asset('admin/assets/js/pages/dashboard-2.init.js') }} "></script>
        <script src="{{ asset('admin/assets/js/pages/ecommerce.init.js') }}"></script>
        <script src="{{ asset('admin/assets/js/app.js') }}"></script>

        @yield('footer')

        <!--Custom App Js-->
		<script>
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

            $(function () {
				// Replace the <textarea id="editor1"> with a CKEditor
				// instance, using default configuration.
				CKEDITOR.replace('editor1')
				// bootstrap WYSIHTML5 - text editor
				$('.textarea').wysihtml5()
			});

			$(function () {
				// Replace the <textarea id="editor2"> with a CKEditor
				// instance, using default configuration.
				CKEDITOR.replace('editor2')
				// bootstrap WYSIHTML5 - text editor
				$('.textarea').wysihtml5()
			});
		</script>

    </body>
</html>
