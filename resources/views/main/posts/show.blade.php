@extends('main.layouts.app')
@section('header')
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
   <link rel='stylesheet' href='https://unpkg.com/emoji.css/dist/emoji.min.css'>
   <style>
      body {
         overflow-y: scroll;
      }

      .data {
         z-index: -1;
         margin: 50px 0;
      }

      input[type=checkbox] {
         /* Double-sized Checkboxes */
         -ms-transform: scale(2);
         /* IE */
         -moz-transform: scale(2);
         /* FF */
         -webkit-transform: scale(2);
         /* Safari and Chrome */
         -o-transform: scale(2);
         /* Opera */
         transform: scale(2);
         padding: 10px;
      }

      /* أنماط الأزرار */
      .feature-post-btn {
         background: linear-gradient(45deg, #ffc107, #ffb300);
         color: #1a1a1a;
         border: 1px solid rgba(0, 0, 0, .1);
         padding: 0.6rem 1.2rem;
         border-radius: 8px;
         font-weight: 600;
         display: inline-flex;
         align-items: center;
         gap: 0.5rem;
         transition: all 0.2s ease;
         text-decoration: none;
      }

      .feature-post-btn:hover {
         transform: translateY(-1px);
         box-shadow: 0 4px 12px rgba(0, 0, 0, .1);
         color: #000;
      }

      .feature-post-btn i {
         font-size: 1.1rem;
      }

      .feature-post-btn.disabled {
         background: #e9ecef;
         color: #6c757d;
         cursor: not-allowed;
      }

      /* أنماط شارة الإعلان المميز */
      .featured-badge {
         display: inline-flex;
         align-items: center;
         gap: 6px;
         background-color: rgba(255, 193, 7, 0.15);
         color: #ffc107;
         padding: 0.4rem 0.8rem;
         border-radius: 999px;
         font-size: 14px;
         font-weight: 600;
         margin-bottom: 1rem;
      }

      /* تنسيق Tooltip */
      .tooltip {
         font-family: 'Cairo', sans-serif !important;
         opacity: 1 !important;
      }
      
      .tooltip .tooltip-inner {
         background-color: #2d3436 !important;
         color: white !important;
         padding: 8px 12px !important;
         font-size: 14px !important;
         max-width: 300px !important;
         box-shadow: 0 2px 10px rgba(0,0,0,0.2) !important;
         border-radius: 6px !important;
      }

      .tooltip.bs-tooltip-top .tooltip-arrow::before {
         border-top-color: #2d3436 !important;
      }
   </style>
@endsection

@section('content')
<!-- product block -->
<section class="product-block mb-4">
   <div class="container">
      @auth
         @if($post->user_id == auth()->id())
            <div class="mb-4 d-flex justify-content-end">
               @if(!$post->is_featured)
                  @php
                     $featuredService = new App\Services\FeaturedPostService();
                     $canFeature = $featuredService->canFeaturePost();
                  @endphp
                  @if(!$canFeature)
                     <span 
                        class="d-inline-block" 
                        tabindex="0" 
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="عذراً، جميع الأماكن المميزة محجوزة حالياً (الحد الأقصى 4 إعلانات)">
                        <button class="feature-post-btn disabled" style="pointer-events: none;" disabled>
                           <i class="fa fa-star"></i>
                           تمييز الإعلان
                        </button>
                     </span>
                  @else
                     <button onclick="featurePost()" class="feature-post-btn">
                        <i class="fa fa-star"></i>
                        تمييز الإعلان
                     </button>
                  @endif
               @else
                  <div class="featured-badge">
                     <i class="fa fa-star"></i>
                     إعلان مميز
                     @if($post->featured_until)
                        <span class="text-muted ms-2">(ينتهي: {{ $post->featured_until->diffForHumans() }})</span>
                     @endif
                  </div>
               @endif
            </div>
         @endif
      @endauth

      <div class="product-info--item mt-4">
         <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
            <p class="h3">
               (تنصح منصة شريكي بالإجتماع المباشر ودراسة الفرص دراسة وافية وذلك في سبيل تقليل احتمالية
               حدوث لأي مخاطر متعلقة, كما تنصح المنصة بالالتزام بالشروط والأحكام والإرشادات والتوجيهات
               الموجودة في الموقع)
            </p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>
      </div>

      <div class="product-body mt-4">
         <div class="row">
            <div class="col-lg-8">
               <div class="product-image mb-3 border-radius-medium overflow-hidden">
                  <img src="{{ $post->img != null ? $post->img_path : $post->category->img_path ?? '' }}"
                     class="img-fluid" alt="...">
               </div>

               <div class="product-infos">
                  <div class="product-info--wrap border-bottom pb-3 mb-3">
                     <div class="product-info--item ">
                        <div class="product-date text-dark-content d-flex align-items-center">
                           <svg class="fill-gold" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24"
                              width="18px" fill="#000000">
                              <path d="M0 0h24v24H0V0z" fill="none" />
                              <path
                                 d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2zm-7 5h5v5h-5z" />
                           </svg>
                           <strong
                              class="d-inline-block ms-1">{{ date('d/m/Y', strtotime($post->created_at))  }}</strong>
                        </div>
                        <h3 class="h3 text-dark-heading">{{ $post->title }}</h3>
                     </div>
                  </div>
                  <div class="product-info--wrap border-bottom pb-3 mb-3">

                     <div class="row">
                        <div class="col-md-6 my-3">
                           <div class="product-info--item d-flex">
                              <div class="sm-icon">
                                 <svg class="fill-gold" xmlns="http://www.w3.org/2000/svg" height="24px"
                                    viewBox="0 0 24 24" width="24px" fill="#000000">
                                    <path d="M0 0h24v24H0V0z" fill="none" />
                                    <path
                                       d="M2.53 19.65l1.34.56v-9.03l-2.43 5.86c-.41 1.02.08 2.19 1.09 2.61zm19.5-3.7L17.07 3.98c-.31-.75-1.04-1.21-1.81-1.23-.26 0-.53.04-.79.15L7.1 5.95c-.75.31-1.21 1.03-1.23 1.8-.01.27.04.54.15.8l4.96 11.97c.31.76 1.05 1.22 1.83 1.23.26 0 .52-.05.77-.15l7.36-3.05c1.02-.42 1.51-1.59 1.09-2.6zm-9.2 3.8L7.87 7.79l7.35-3.04h.01l4.95 11.95-7.35 3.05z" />
                                    <circle cx="11" cy="9" r="1" />
                                    <path d="M5.88 19.75c0 1.1.9 2 2 2h1.45l-3.45-8.34v6.34z" />
                                 </svg>
                              </div>
                              <div class="ms-3">
                                 <h3 class="h3 text-dark-heading">نوع الفرصة</h3>
                                 <h4 class="h4 text-dark-heading">{{ $post->sort }}</h4>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6 my-3">
                           <div class="product-info--item d-flex">
                              <div class="sm-icon">
                                 <svg class="fill-gold" xmlns="http://www.w3.org/2000/svg" height="24px"
                                    viewBox="0 0 24 24" width="24px" fill="#000000">
                                    <path d="M0 0h24v24H0V0z" fill="none" />
                                    <path
                                       d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0-6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm0 8c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4zm-6 4c.22-.72 3.31-2 6-2 2.7 0 5.8 1.29 6 2H9zm-3-3v-3h3v-2H6V7H4v3H1v2h3v3z" />
                                 </svg>
                              </div>
                              <div class="ms-3">
                                 @php $partner_sort = $post->partner_sort;
                                    $partner_sort = json_decode($partner_sort);
                                 $partner_sort = (array) $partner_sort;  @endphp
                                 <h3 class="h3 text-dark-heading">نوع الشراكة</h3>
                                 @if(isset($partner_sort[0]))
                                    <h4 class="h4 text-dark-heading">شراكة</h4>
                                 @endif

                                 @if(isset($partner_sort[1]))
                                    <h4 class="h4 text-dark-heading"> تمويل (قرض)</h4>
                                 @endif
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6 my-3">
                           <div class="product-info--item d-flex">
                              <div class="sm-icon">
                                 <svg class="fill-gold" xmlns="http://www.w3.org/2000/svg"
                                    enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px"
                                    fill="#000000">
                                    <rect fill="none" height="24" width="24" />
                                    <g>
                                       <path
                                          d="M4,13c1.1,0,2-0.9,2-2c0-1.1-0.9-2-2-2s-2,0.9-2,2C2,12.1,2.9,13,4,13z M5.13,14.1C4.76,14.04,4.39,14,4,14 c-0.99,0-1.93,0.21-2.78,0.58C0.48,14.9,0,15.62,0,16.43V18l4.5,0v-1.61C4.5,15.56,4.73,14.78,5.13,14.1z M20,13c1.1,0,2-0.9,2-2 c0-1.1-0.9-2-2-2s-2,0.9-2,2C18,12.1,18.9,13,20,13z M24,16.43c0-0.81-0.48-1.53-1.22-1.85C21.93,14.21,20.99,14,20,14 c-0.39,0-0.76,0.04-1.13,0.1c0.4,0.68,0.63,1.46,0.63,2.29V18l4.5,0V16.43z M16.24,13.65c-1.17-0.52-2.61-0.9-4.24-0.9 c-1.63,0-3.07,0.39-4.24,0.9C6.68,14.13,6,15.21,6,16.39V18h12v-1.61C18,15.21,17.32,14.13,16.24,13.65z M8.07,16 c0.09-0.23,0.13-0.39,0.91-0.69c0.97-0.38,1.99-0.56,3.02-0.56s2.05,0.18,3.02,0.56c0.77,0.3,0.81,0.46,0.91,0.69H8.07z M12,8 c0.55,0,1,0.45,1,1s-0.45,1-1,1s-1-0.45-1-1S11.45,8,12,8 M12,6c-1.66,0-3,1.34-3,3c0,1.66,1.34,3,3,3s3-1.34,3-3 C15,7.34,13.66,6,12,6L12,6z" />
                                    </g>
                                 </svg>
                              </div>
                              <div class="ms-3">
                                 <h3 class="h3 text-dark-heading">عدد الشركاء</h3>
                                 <h4 class="h4 text-dark-heading">{{ $post->partners_no }} بشكل أقصى للمشروع</h4>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6 my-3">
                           <div class="product-info--item d-flex">
                              <div class="sm-icon">
                                 <svg class="fill-gold" xmlns="http://www.w3.org/2000/svg"
                                    enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px"
                                    fill="#000000">
                                    <g>
                                       <rect fill="none" height="24" width="24" />
                                    </g>
                                    <g>
                                       <g>
                                          <path
                                             d="M12,16c3.87,0,7-3.13,7-7c0-3.87-3.13-7-7-7C8.13,2,5,5.13,5,9C5,12.87,8.13,16,12,16z M12,4c2.76,0,5,2.24,5,5 s-2.24,5-5,5s-5-2.24-5-5S9.24,4,12,4z" />
                                          <circle cx="10" cy="8" r="1" />
                                          <circle cx="14" cy="8" r="1" />
                                          <circle cx="12" cy="6" r="1" />
                                          <path d="M7,19h2c1.1,0,2,0.9,2,2v1h2v-1c0-1.1,0.9-2,2-2h2v-2H7V19z" />
                                       </g>
                                    </g>
                                 </svg>
                              </div>
                              <div class="ms-3">
                                 <h3 class="h3 text-dark-heading"> المهارات المطلوبة </h3>
                                 <h4 class="h4 text-dark-heading tags">
                                    @foreach ($tags as $tag)
                                       {{ json_decode($tag->name)->en }}
                                    @endforeach



                                 </h4>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6 my-3">
                           <div class="product-info--item d-flex">
                              <div class="sm-icon">
                                 <svg class="fill-gold" xmlns="http://www.w3.org/2000/svg" height="24px"
                                    viewBox="0 0 24 24" width="24px" fill="#000000">
                                    <path d="M0 0h24v24H0V0z" fill="none" />
                                    <path
                                       d="M16.24 7.75c-1.17-1.17-2.7-1.76-4.24-1.76v6l-4.24 4.24c2.34 2.34 6.14 2.34 8.49 0 2.34-2.34 2.34-6.14-.01-8.48zM12 1.99c-5.52 0-10 4.48-10 10s4.48 10 10 10 10-4.48 10-10-4.48-10-10-10zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z" />
                                 </svg>
                              </div>
                              <div class="ms-3">
                                 <h3 class="h3 text-dark-heading"> ساعات العمل</h3>
                                 <h4 class="h4 text-dark-heading">{{ $post->weeks_hours ?? 'لايوجد'}}</h4>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="product-info--wrap pb-3 mb-3">
                     <div class="product-info--item">
                        <h3 class="h3 text-dark-heading">عن الفرصة</h3>
                        {!! $post->body !!}
                     </div>
                  </div>

               </div>
            </div>
            <!-- side card -->
            <div class="col-lg-4 col-md-12">
               <div class="personal-infos p-sticky">
                  <div class="card box-shadow-medium border-radius-medium overflow-hidden">
                     <div class="card-header border-none bg-blue-200 text-center py-4">
                        <h3 class="h4 text-dark-heading">المبلغ المطلوب</h3>
                        <h2 class="h3 text-blue-light-heading mb-0">{{ number_format($post->price) }} ريال</h2>
                     </div>
                     <div class="card-body mt-2 p-4">
                        <div class="personal-info--item mb-4">
                           <h4 class="h4 text-dark-heading">هذا الإعلان بواسطة</h4>
                           <h5 class="h5 text-dark-content">
                              {{  $post->user_id == 0 ? 'إدارة الموقع' : $post->user->name ?? ' '  }}</h5>
                        </div>
                        <div class="personal-info--item mb-4">
                           <h4 class="h4 text-dark-heading">المنطقة</h4>
                           <h5 class="h5 text-dark-content">
                              {{ $post->area->name ?? 'غير محدد' }}-{{ $post->parentArea->name ?? 'غير محدد' }}</h5>
                        </div>
                        @guest
                           <a class="btn main-btn bg-blue-200 text-blue-light-heading border-none d-flex medium-btn
                                 rounded align-items-center justify-content-center" data-bs-toggle="modal"
                              data-bs-target="#needToSign">
                              <strong class="d-block"> رقم التواصل</strong>
                              <div class="shareeki-icon">
                                 <img src="{{ asset('main/images/7.png') }}" alt="">
                              </div>
                           </a>
                        @else
                        <div class="collapse" id="showNumber">
                           <div>
                              {{-- <div class="personal-info--item mb-4">
                                 <h4 class="h4 text-dark-heading">الجوال</h4>
                                 <h5 class="h5 text-dark-content">
                                    {{ $post->phone }}
                                 </h5>
                              </div> --}}
                              @if($post->email == 1)
                                 <div class="personal-info--item mb-4">
                                    <h4 class="h4 text-dark-heading">البريد الاكتروني</h4>
                                    <h5 class="h5 text-dark-content">
                                       {{ $post->user->email ?? 'غير متاح الان' }}
                                    </h5>
                                 </div>
                              @endif

                              <div class="personal-info--item text-center">
                                 <h4 class="h4 text-gold-heading">نتمنى لك رحلة شراكة سعيدة</h4>
                                 <div class="shareeki-icon mx-auto">
                                    <img src="{{ asset('main/images/7.png') }}" alt="">
                                 </div>

                              </div>
                           </div>
                        </div>
                        <a onclick="like()"
                           class="btn main-btn bg-blue-200 text-blue-light-heading border-none d-flex medium-btn rounded align-items-center justify-content-center"
                           data-bs-toggle="collapse" href="#showNumber" role="button" aria-expanded="false"
                           aria-controls="showNumber">
                           <strong class="d-block"> رقم التواصل</strong>
                           <div class="shareeki-icon">
                              <img src="{{ asset('main/images/7.png') }}" alt="">
                           </div>
                        </a>
                     </div>
                     @endif
                  </div>

                  @auth
                     <div class="text-center">
                        <button onclick="reportPost()" type="button"
                           class="btn toast-btn main-btn text-red-heading text-btn medium-btn mt-2 rounded-btn"
                           id="reportingAlert">
                           <i class="far fa-flag"></i>
                           أبلغ عن شبهة الإحتيال
                        </button>
                     </div>
                  @endauth

               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<!--  -->

<!-- الإبلاغ عن الإعلان -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 5">
   <div class="toast align-items-center hide bg-success" id="reportingAlert" role="alert" aria-live="assertive"
      aria-atomic="true">
      <div class="d-flex">
         <div class="toast-body">
            <h3 class="h4 text-white-heading">
               تم الإبلاغ عن الإعلان .... إدارة الموقع ستراجع البلاغ المقدم!
            </h3>
         </div>
         <button type="button" class="btn-close me-2 m-auto btn-close-white" data-bs-dismiss="toast"
            aria-label="Close"></button>
      </div>
   </div>
</div>

<!-- Modal -->
<div class="modal fade" id="needToSign" tabindex="-1" aria-labelledby="needToSignLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <!-- <div class="modal-header px-4">
                     <h5 class="modal-title" id="needToSignLabel">ساعدنا نعرف رغبتك</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div> -->
         <div class="modal-body p-4 my-5 text-center">
            <svg id="enter" xmlns="http://www.w3.org/2000/svg" width="129.282" height="129.282"
               viewBox="0 0 129.282 129.282">
               <path id="Path_632" data-name="Path 632"
                  d="M105.041,129.282H24.24A24.239,24.239,0,0,1,0,105.041V24.24A24.239,24.239,0,0,1,24.24,0h80.8a24.239,24.239,0,0,1,24.24,24.24v80.8A24.239,24.239,0,0,1,105.041,129.282Zm0,0"
                  fill="#d8b649" opacity="0.15" />
               <path id="Path_633" data-name="Path 633"
                  d="M216.023,141.465a2.7,2.7,0,0,1-2.695-2.695V136.08a8.088,8.088,0,0,1,8.08-8.08h28.955a2.693,2.693,0,1,1,0,5.385H221.408a2.7,2.7,0,0,0-2.695,2.695v2.695a2.688,2.688,0,0,1-2.691,2.691Zm0,0"
                  transform="translate(-159.462 -95.679)" fill="#ecc957" />
               <path id="Path_634" data-name="Path 634"
                  d="M230.849,304.16h-9.425a8.088,8.088,0,0,1-8.08-8.08v-5.385a2.693,2.693,0,1,1,5.385,0v5.385a2.7,2.7,0,0,0,2.695,2.695h9.425a2.693,2.693,0,1,1,0,5.385Zm0,0"
                  transform="translate(-159.474 -215.279)" fill="#ecc957" />
               <g id="Group_761" data-name="Group 761" transform="translate(32.32 32.33)">
                  <path id="Path_635" data-name="Path 635"
                     d="M146.063,207.479a2.691,2.691,0,0,1-4.6-1.9V197.5H130.694a2.695,2.695,0,1,1,0-5.389h10.775v-8.08a2.692,2.692,0,0,1,4.6-1.9L156.841,192.9a2.7,2.7,0,0,1,0,3.809Zm0,0"
                     transform="translate(-128 -167.877)" fill="#d8b649" />
                  <path id="Path_636" data-name="Path 636"
                     d="M297.16,128.292l-16.184,5.393a5.425,5.425,0,0,0-3.648,5.115v48.481a5.392,5.392,0,0,0,5.385,5.385,5.677,5.677,0,0,0,1.717-.262l16.188-5.4a5.42,5.42,0,0,0,3.645-5.11V133.415a5.444,5.444,0,0,0-7.1-5.123Zm0,0"
                     transform="translate(-239.622 -128.039)" fill="#d8b649" />
               </g>
            </svg>
            <p class="text-dark-content mt-3">
               الرجاء تسجيل الدخول
            </p>
            <div class="alert alert-info text-start">
               <h6>تفاصيل الإعلان:</h6>
               <ul>
                  <li><strong>قيمة الإعلان:</strong> 130.00 ريال</li>
                  <li><strong>ضريبة القيمة المضافة:</strong> 19.50 ريال</li>
                  <li><strong>الإجمالي:</strong> 149.50 ريال</li>
                  <li><strong>المدة:</strong> من {{ now()->format('Y-m-d') }} إلى {{ now()->addMonths(3)->format('Y-m-d') }}</li>
               </ul>
            </div>
            <a href="{{ asset('login') }}" type="button" class="btn main-btn gold-btn medium-btn rounded"> تسجيل الدخول
            </a>
            <button type="button" class="btn main-btn blue-btn medium-btn rounded" data-bs-dismiss="modal"> ربما
               لاحقا </button>
         </div>
      </div>
   </div>
</div>
@endsection

@section('footer')
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
   <script>
      // تفعيل جميع tooltips
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
         return new bootstrap.Tooltip(tooltipTriggerEl)
      });

      $(document).ready(function () {
      });

      function featurePost() {
         @auth
            Swal.fire({
               title: 'تأكيد تمييز الإعلان',
               html: `
                          <div class="text-right">
                              <p>تكلفة تمييز الإعلان: <strong>149.50</strong> ريال</p>
                              <p>مدة التمييز: <strong>3</strong> أشهر</p>
                              <p>سيظهر إعلانك في القسم المميز في أعلى الصفحة الرئيسية</p>
                          </div>
                      `,
               icon: 'info',
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: 'نعم، تمييز الإعلان',
               cancelButtonText: 'إلغاء'
            }).then((result) => {
               if (result.isConfirmed) {
                  axios.post('/posts/{{ $post->id }}/feature')
                     .then(function (response) {
                        if (response.data.success) {
                           if (response.data.redirect_url) {
                              // تحويل المستخدم مباشرة إلى صفحة الدفع
                              window.location.href = response.data.redirect_url;
                           } else {
                              Swal.fire('تم!', response.data.message, 'success')
                                 .then(() => {
                                    location.reload();
                                 });
                           }
                        } else {
                           Swal.fire('خطأ!', response.data.message, 'error');
                        }
                     })
                     .catch(function (error) {
                        console.error('Error:', error);
                        Swal.fire('خطأ!', error.response?.data?.message || 'حدث خطأ أثناء معالجة طلبك', 'error');
                     });
               }
            });
         @endauth
         @guest
            swalMessageIfUnauthenticated();
         @endguest
       }
      function reportPost() {
         console.log(333)
         @auth
            let report = {
               post_id: {!! $post->id !!},
            }
            axios.post('../reportpost', report)
               .then((data) => {

                  $('.report').append('<div class="alert alert-success mt-3" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>تم الإبلاغ عن الإعلان .... إدارة الموقع ستراجع البلاغ المقدم!</div></div>');
                  setTimeout(() => {
                     $(".alert").fadeTo(500, 0).slideUp(500, function () {
                        $(this).remove()
                     });
                  }, 2000);

               }).catch((error) => {

               })
         @endauth
         @guest
            swalMessageIfUnauthenticated();
         @endguest
               }


      function like() {
         console.log(359)
         @guest
            swalMessageIfUnauthenticated();
         @endguest
            @auth


                             var userVerified = {!! date("Y-m-d", strtotime(auth()->user()->email_verified_at)) !!};
               //console.log(userVerified);
               //alert(userVerified);
               if (userVerified == 1968) {
                  swalMessageIfUnauthenticatedOne();
                  return;
               }



               axios.post('../like', { post_id: {!! $post->id !!} })
                  .then((data) => {
                     $('#likes').html(0);
                     $('#likes').html(data.data)
                     $('.the_contact').css('display', 'block');
                  })
            @endauth

           }


      function dislike() {
         console.log(388)
         @guest
            swalMessageIfUnauthenticated();
         @endguest
         @auth
            axios.post('../check', { post_id: {!! $post->id !!} })
               .then((data) => {
                  if (data.data.length == 0) { window.checkuserdislike = 0; } else { window.checkuserdislike = 1; }
                  axios.post('../dislike', { post_id: {!! $post->id !!} })
                     .then((data) => {
                        $('#dislikes').html(parseInt($('#dislikes').html(), 10) + 1)
                        if (checkuserdislike != 0) {
                           $('#likes').html(parseInt($('#dislikes').html(), 10) - 1)
                        }
                     })
               })
         @endauth
           }

      function swalMessageIfUnauthenticated() {
         Swal.fire({
            icon: 'error',
            position: 'center',
            type: 'error',
            title: "تنبيه",
            html:
               '<h5>الرجاء تسجيل الدخول أو الإنضمام للموقع</h5> <br/> ' +
               '<a class="btn btn-info" href="{{ route("login") }}">دخول الموقع</a> ' +
               '<a class="btn btn-info" href="{{ route("register") }}">الإنضمام للموقع</a> ' +
               '<a class="btn btn-info" onclick="swal.closeModal(); return false;">شكراً ... ربما لاحقاً</a> ',
            showConfirmButton: false,

         })
      }


      function swalMessageIfUnauthenticatedOne() {
         Swal.fire({
            icon: 'error',
            position: 'center',
            type: 'error',
            title: "تنبيه",
            html:
               '<h5>الرجاء تفعيل الحساب الخاص  بك</h5> <br/> ' +
               '<a class="btn btn-info" href="{{ route("verification.notice") }}">تفعيل الحساب</a> ',
            showConfirmButton: false,

         })
      }

      function showContacts() {
         $('.show_contact').css('display', 'none');
         $('.the_contact').css('display', 'block');
      }

   </script>
@endsection