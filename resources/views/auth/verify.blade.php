@extends('main.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-right">{{-- __('Verify Your Email Address') --}} تأكيد البريد الإلكتروني</div>

                <div class="card-body text-right">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{-- __('A fresh verification link has been sent to your email address.') --}}
                            تم إرسال رابط جديد لبريدك الإلكتروني
                        </div>
                    @endif

                    تم ارسال رسالة تفعيل الى بريدك الإلكتروني, نأمل منك تفعيل الحساب لتتمكن من المواصلة.  قد تكون الرسالة في بريد الـSpam
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{-- __('click here to request another') --}}
                        أطلب رابط اخر للتأكيد    
                        </button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
