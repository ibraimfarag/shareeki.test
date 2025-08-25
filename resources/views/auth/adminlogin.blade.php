<!DOCTYPE html>
<html lang="en" dir="rtl">

    <head>
        <meta charset="utf-8" />
        <title>لوحة التحكم | تسجيل الدخول</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Noura" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('admin/assets/images/favicon.ico') }}"> 
        <!-- Bootstrap Css -->
        <link href="{{ asset('admin/assets/css/bootstrap-rtl.min.css') }} " id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('admin/assets/css/icons.min.css') }} " rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('admin/assets/css/app-rtl.min.css') }} " id="app-style" rel="stylesheet" type="text/css" />
    </head>

    <body style="background: url({{asset('users/images/tic-tac-toe.png')}});position: absolute;height: 100%;width: 100%;top: 0;">
        <div class="account-pages my-5 pt-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div style="background-color: #fdfdfd;" class="card overflow-hidden">
                            <div class="card-body pt-0">

                                <h3 class="text-center mt-5 mb-4">
                                    <a href="{{ asset('adminpanel') }}" class="d-block auth-logo">
                                        <img src="{{ asset('main/images/logo.png') }}" alt="" height="30" class="auth-logo-dark">
                                    </a>
                                </h3>

								@if ($errors->has('email') || $errors->has('password'))
									<div class="alert alert-icon text-center alert-danger" role="alert">
										<i class="fa fa-frown-o mr-2" aria-hidden="true"></i>  اسم المستخدم او كلمة المرور غير صحيحة ! حاول مرة اخرى
									</div>
								@endif

                                <div class="p-3">
                                    <form method="POST" action="{{ route('admin.login') }}" class="form-horizontal mt-4" >
                                        @csrf
                                        <div class="mb-3">
											<label for="username">اسم المستخدم</label>
											<input type="email" name="email" class="form-control" id="email"  placeholder="اسم المستخدم" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="password">كلمة المرور</label>
										    <input type="password" name="password" class="form-control" id="password" placeholder="كلمة المرور" required>
                                        </div>

                                        <div class="mb-3 row mt-4">
                                            <div class="col-6">
                                                <button class="btn btn-primary w-md waves-effect waves-light" type="submit">تسجيل الدخول</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 text-center">
							© <script>document.write(new Date().getFullYear())</script> جميع الحقوق محفوظة <span class="d-none d-sm-inline-block"> <i class="mdi mdi-heart text-danger"></i> Shareeki.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- JAVASCRIPT -->
        <script src="{{ asset('admin/assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('admin/assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('admin/assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('admin/assets/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ asset('admin/assets/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
        <!-- App js -->
        <script src="{{ asset('admin/assets/js/app.js') }}"></script>
    </body>
</html>