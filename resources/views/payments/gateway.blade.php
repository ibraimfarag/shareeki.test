@extends('main.layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">بوابة الدفع الإلكتروني</h4>
                    </div>
                    <div class="card-body p-0">
                        <!-- إطار بوابة الدفع -->
                        <iframe id="paymentFrame" src="{{ $paymentUrl }}" style="width: 100%; height: 600px; border: none;"
                            allow="payment">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // استمع لرسائل من iframe
            window.addEventListener('message', function (event) {
                // تأكد من أن الرسالة من بوابة الدفع
                if (event.origin === "{{ parse_url(config('rajhi.payment_url'), PHP_URL_SCHEME) . '://' . parse_url(config('rajhi.payment_url'), PHP_URL_HOST) }}") {
                    if (event.data.status === 'success') {
                        window.location.href = "{{ url('/payments/rajhi/success') }}";
                    } else if (event.data.status === 'error') {
                        window.location.href = "{{ url('/payments/rajhi/error') }}";
                    }
                }
            });
        });
    </script>
@endsection