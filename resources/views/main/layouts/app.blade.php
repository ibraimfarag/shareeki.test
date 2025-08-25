<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="apple-touch-icon" href="{{ asset('main/images/shareeki-fivicon.png') }} " sizes="180x180">
   <link rel="icon" href="{{ asset('main/images/shareeki-fivicon.png') }} " sizes="32x32" type="image/png">
   <link rel="icon" href="{{ asset('main/images/shareeki-fivicon.png') }} " sizes="16x16" type="image/png">
   <!-- CSRF Token -->
   <meta name="csrf-token" content="{{ csrf_token() }}">

   <title>{{ $settings->name }}</title>
   <link rel="preconnect" href="https://fonts.gstatic.com">
   <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
   <link rel="stylesheet" href="{{ asset('main/css/bootstrap-select.min.css') }} ">
   <link rel="stylesheet" href="{{ asset('main/css/bootstrap.rtl.min.css') }} ">
   <link rel="stylesheet" href="{{ asset('main/css/style.css') }} ">
   @yield('header')
   
   <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-K6G5NGSVH5"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-K6G5NGSVH5');
</script>

</head>

<body>

   <main class="page">

      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-light main-navbar border-bottom">
         <div class="container">

            <a class="navbar-brand " href="{{ url('/') }}">
               <img class="_logo" src="{{ asset('main/images/logo.png') }}" alt="">
            </a>
            <button class="navbar-toggler mr-3" type="button" data-bs-toggle="collapse" data-bs-target="#navCollapse"
               aria-controls="navCollapse" aria-expanded="false" aria-label="Toggle navigation">
               <svg id="Layer_1" enable-background="new 0 0 128 128" height="30px" viewBox="0 0 128 128" width="30px"
                  xmlns="http://www.w3.org/2000/svg">
                  <path id="Menu"
                     d="m108 36h-88c-2.209 0-4-1.791-4-4s1.791-4 4-4h88c2.209 0 4 1.791 4 4s-1.791 4-4 4zm4 28c0-2.209-1.791-4-4-4h-88c-2.209 0-4 1.791-4 4s1.791 4 4 4h88c2.209 0 4-1.791 4-4zm0 32c0-2.209-1.791-4-4-4h-88c-2.209 0-4 1.791-4 4s1.791 4 4 4h88c2.209 0 4-1.791 4-4z" />
               </svg>
            </button>

            <div class="collapse navbar-collapse" id="navCollapse">
               <ul class="navbar-nav me-auto">
                  <li class="nav-item hover-item rounded active">
                     <a class="nav-link" href="{{ url('/') }}" data-scroll="application-areas"
                        data-localize="menu.application">الصفحة
                        الرئيسية<span class="sr-only">(current)</span></a>
                  </li>

                  <li class="nav-item hover-item rounded">
                     <a class="nav-link" href="{{ route('the_page', 'about') }}" data-scroll="about-us" data-localize="menu.abouts">من
                        نحن</a>
                  </li>

                  <li class="nav-item hover-item rounded">
                     <a class="nav-link" href="{{ route('contact_us') }}" data-scroll="contact" data-localize="menu.contact">
                        تواصل معنا
                     </a>
                  </li>
                  <li class="nav-item hover-item rounded">
                     <a class="nav-link" href="{{ route('the_page', 'terms') }}">
                        الشروط والأحكام
                     </a>
                  </li>
                  <li class="nav-item hover-item rounded">
                     <a class="nav-link" href="{{ route('the_page', 'faqs') }}">
                        ارشادات عامّة
                     </a>
                  </li>
               </ul>

               <div class="d-flex flex-lg-row flex-md-column col-md-reverse">
                  <a href="{{ route('the_page', 'commission') }}" class="btn main-btn blue-btn medium-btn rounded-btn me-2 mb-lg-auto mb-3">
                     ادفع عمولة الموقع الآن!
                  </a>
                  <a href="{{ route('the_posts.create') }}"
                     class="btn main-btn gold-btn medium-btn rounded-btn me-2 mb-lg-auto mb-3">
                     أضف فرصة!
                  </a>
                  <div class="border-right mx-2"></div>
                  <!--  -->
                  @guest
                     <a href="{{ route('login') }}" class="btn main-btn text-btn medium-btn rounded-btn">
                        تسجيل الدخول
                     </a>
                  @else
                     <div class="megamenu dropdown h-100 mb-lg-auto mb-3">
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" data-bs-toggle="dropdown"
                           aria-expanded="false">
                           {{ auth()->user()->name }}
                           <i class="fas fa-chevron-down"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="servicesDropdown">
                           <div class="dropdown-menu-content row">
                              <div class="col-lg-3 col-md-12 my-2">
                                 <div class="megamenu-item">
                                    <a href="{{ route('home') }}" class="d-block h-100">
                                       <div class="card border card-transition p-4 text-center h-100">
                                          <div class="sx-icon crlc-icon bg-gold-light  p-2 mb-3 mx-auto">
                                             <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                                                width="24px" fill="#000000">
                                                <path d="M0 0h24v24H0V0z" fill="none" />
                                                <path
                                                   d="M12 6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2m0 9c2.7 0 5.8 1.29 6 2v1H6v-.99c.2-.72 3.3-2.01 6-2.01m0-11C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 9c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z" />
                                             </svg>
                                          </div>
                                          <h4 class="h4 text-dark-heading mb-0">
                                             الملف الشخصي
                                          </h4>
                                       </div>
                                    </a>

                                 </div>
                              </div>
                              <div class="col-lg-3 col-md-12 my-2">
                                 <div class="megamenu-item">
                                    <a href="{{ route('get_personal_info') }}">
                                       <div class="card border card-transition p-4 text-center ">
                                          <div class="sx-icon crlc-icon bg-gold-light p-2 mb-3 mx-auto">
                                             <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                                                width="24px" fill="#000000">
                                                <path d="M0 0h24v24H0V0z" fill="none" />
                                                <path
                                                   d="M12 6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2m0 9c2.7 0 5.8 1.29 6 2v1H6v-.99c.2-.72 3.3-2.01 6-2.01m0-11C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 9c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z" />
                                             </svg>
                                          </div>
                                          <h4 class="h4 text-dark-heading mb-0">
                                             تعديل المعلومات الشخصية
                                          </h4>
                                       </div>
                                    </a>
                                 </div>
                              </div>
                              <div class="col-lg-3 col-md-12 my-2">
                                 <div class="megamenu-item">
                                    <a href="{{ route('get_change_password') }}">
                                       <div class="card border card-transition p-4 text-center">
                                          <div class="sx-icon crlc-icon bg-gold-light p-2 mb-3 mx-auto">
                                             <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                                                width="24px" fill="#000000">
                                                <g fill="none">
                                                   <path d="M0 0h24v24H0V0z" />
                                                   <path d="M0 0h24v24H0V0z" opacity=".87" />
                                                </g>
                                                <path
                                                   d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z" />
                                             </svg>
                                          </div>
                                          <h4 class="h4 text-dark-heading mb-0">
                                             تعديل كلمة المرور
                                          </h4>
                                       </div>
                                    </a>
                                 </div>
                              </div>
                              <div class="col-lg-3 col-md-12 my-2">
                                 <div class="megamenu-item">
                                    <a href="{{ asset('userlogout') }}">
                                       <div class="card border card-transition p-4 text-center">
                                          <div class="sx-icon crlc-icon bg-gold-light p-2 mb-3 mx-auto">
                                             <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                                                width="24px" fill="#000000">
                                                <path d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                   d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z" />
                                             </svg>
                                          </div>
                                          <h4 class="h4 text-dark-heading mb-0">
                                             تسجيل الخروج
                                          </h4>
                                       </div>
                                    </a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  @endif
                  <!--  -->

               </div>
            </div>
         </div>
      </nav>
      <!--  -->

      <!--  -->
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('message'))
                <div class="alert alert-success" role="alert">
                    {{ session('message') }}
                </div>
            @endif
        </div>
      @yield('content')

   </main>
   <!--  -->

   <!-- Footer -->
   <footer class="bg-blue-dark">
      <div class="container">
         <div class="footer-wrap d-flex justify-content-between">

            <div class="footer-link-group mx-auto">
               <ul class="navbar-nav flex-wrap">
                  <li class="nav-item">
                     <a class="nav-link text-white-heading" href="{{ url('/') }}">الصفحة
                        الرئيسية<span class="sr-only">(current)</span></a>
                  </li>

                  <li class="nav-item">
                     <a class="nav-link text-white-heading" href="{{ route('the_page', 'about') }}" data-scroll="about-us">من نحن</a>
                  </li>

                  <li class="nav-item">
                     <a class="nav-link text-white-heading" href="{{ route('contact_us') }}">
                        تواصل معنا
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link text-white-heading" href="{{ route('the_page', 'terms') }}">
                        الشروط والأحكام
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link text-white-heading" href="{{ route('the_page', 'faqs') }}">
                        ارشادات عامّة
                     </a>
                  </li>
               </ul>
            </div>
         </div>
      </div>
   </footer>
   <!--  -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.22.0/axios.min.js" integrity="sha512-m2ssMAtdCEYGWXQ8hXVG4Q39uKYtbfaJL5QMTbhl2kc6vYyubrKHhr6aLLXW4ITeXSywQLn1AhsAaqrJl8Acfg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <script src="{{ asset('main/js/jquery.min.js') }}"></script>
   <script src="{{ asset('main/js/bootstrap.bundle.min.js') }}"></script>
   <script src="{{ asset('main/js/bootstrap-select.min.js') }}"></script>
   <script src="{{ asset('main/js/index.js') }}"></script>
 </body>
@yield('footer')
</html>
