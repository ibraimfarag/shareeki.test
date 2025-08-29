@extends('main.layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center">عرض تمييز الإعلان</h1>
        <p class="text-center">استفد من مميزات الإعلان المميز لجذب المزيد من العملاء!</p>

        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">مميزات الإعلان المميز:</h5>
                <ul>
                    <li>ظهور الإعلان في أعلى القائمة</li>
                    <li>زيادة نسبة المشاهدات</li>
                    <li>تصميم مميز لجذب الانتباه</li>
                </ul>

                <h5 class="card-title mt-4">الشروط:</h5>
                <ul>
                    <li>مدة التمييز: 3 أشهر (90 يوم)</li>
                    <li><strong>التكلفة الإجمالية: 149.5 ريال (شاملة ضريبة القيمة المضافة)</strong></li>
                </ul>

                <div class="alert alert-info mt-3">
                    <h6>تفاصيل الفاتورة:</h6>
                    <div class="row">
                        <div class="col-md-8">قيمة الإعلان المميز (من {{ now()->format('Y-m-d') }} إلى
                            {{ now()->addDays(90)->format('Y-m-d') }})</div>
                        <div class="col-md-4 text-end">130.00 ريال</div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">ضريبة القيمة المضافة (15%)</div>
                        <div class="col-md-4 text-end">19.50 ريال</div>
                    </div>
                    <hr>
                    <div class="row font-weight-bold">
                        <div class="col-md-8"><strong>الإجمالي</strong></div>
                        <div class="col-md-4 text-end"><strong>149.50 ريال</strong></div>
                    </div>
                    <small class="text-muted">* سيتم إصدار فاتورة وإيصال تلقائياً</small>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('posts.featured.checkout', $post->id) }}" class="btn btn-success">موافق - ادفع
                        الآن</a>
                    <a href="{{ route('home') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </div>
        </div>
    </div>
@endsection