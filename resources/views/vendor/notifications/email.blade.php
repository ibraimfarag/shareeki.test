@component('mail::message')
<div dir="rtl" style="text-align: right; direction: rtl;">
    <h2 dir="rtl" style="margin-bottom: 0.5rem; text-align: right; direction: rtl; color: #007bff; font-weight: bold;">
        شريكي العزيز 🤝
    </h2>
    <p dir="rtl"
        style="margin-top: 0; font-size: 1.1rem; text-align: right; direction: rtl; color: #007bff; font-weight: lighter;">
        حياك أكمل
        عملية استرداد رمز المرور للدخول عبر المنصة 😎👇🏽</p>
    <p dir="rtl" style="font-size: 1.1rem; text-align: right; direction: rtl; color: #007bff;font-weight: lighter;">اضغط
        على الزر التالي
        لإعادة تعيين كلمة المرور:</p>
    <div dir="rtl" style="text-align: right; direction: rtl;">
        @component('mail::button', ['url' => $actionUrl])
        إعادة تعيين كلمة المرور
        @endcomponent
    </div>
    <p dir="rtl" style="font-size: 1.1rem; text-align: right; direction: rtl; color: #007bff;font-weight: lighter;">إذا
        لم تطلب ذلك، يمكنك
        تجاهل هذا البريد.</p>
    <p dir="rtl"
        style="margin-top: 2rem; font-size: 1.1rem; text-align: right; direction: rtl; color: #007bff;font-weight: lighter;">
        شكراً لك!<br>
        <span style="font-weight: bold; display: inline-flex; align-items: center;">
            فريق شريكي
            <img src="{{ asset('main/images/shareeki-logo.png') }}" alt="شريكي"
                style="height: 24px; vertical-align: middle; margin-right: 6px;">
        </span>
    </p>
</div>
@endcomponent