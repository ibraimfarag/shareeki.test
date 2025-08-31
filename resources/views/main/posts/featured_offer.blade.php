@extends('main.layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="text-center mb-5">
            <h1 class="display-4 text-primary font-weight-bold">✨ باقة شريكي VIP ✨</h1>
            {{-- <p class="lead text-muted">استفد من مميزات الإعلان المميز لجذب المزيد من العملاء وزيادة فرص النجاح!</p>
            --}}
        </div>

        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-body p-5">
                <h5 class="card-title text-success mb-4">ليش تشترك معنا في الباقة!</h5>
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item bg-light">✅ تظهر أولا للمهتمين بالفرص من خلال تثبيت اعلانك دائما على مدار ٣
                        شهور😎 </li>
                    <li class="list-group-item bg-light">✅ راح تنضم تلقائيا لحملات المنصة التسويقية في التواصل الإجتماعي
                        وغيرها😍 </li>
                    <li class="list-group-item bg-light">✅ سيتم توفير الدعم المباشر من خلال تقديم الاستشارة و المساعدة عند
                        الطلب🤝</li>
                </ul>

                <h5 class="card-title text-warning mb-4">📜 الشروط:</h5>
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item bg-light">⏳ مدة التمييز: 3 أشهر (90 يوم)</li>
                    <li class="list-group-item bg-light"><strong>💰 التكلفة الإجمالية: 149.5 ريال (شاملة ضريبة القيمة
                            المضافة)</strong>
                    </li>
                </ul>

                <div class="alert alert-info mt-4 rounded-lg">
                    <h6 class="text-info font-weight-bold">💳 تفاصيل الفاتورة:</h6>
                    <div class="row">
                        <div class="col-md-8">📅 قيمة الإعلان المميز (من {{ now()->format('Y-m-d') }} إلى
                            {{ now()->addDays(90)->format('Y-m-d') }})
                        </div>
                        <div class="col-md-4 text-end">130.00 ريال</div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">💵 ضريبة القيمة المضافة (15%)</div>
                        <div class="col-md-4 text-end">19.50 ريال</div>
                    </div>
                    <hr>
                    <div class="row font-weight-bold">
                        <div class="col-md-8"><strong>💸 الإجمالي</strong></div>
                        <div class="col-md-4 text-end"><strong>149.50 ريال</strong></div>
                    </div>
                    <small class="text-muted">* سيتم إصدار فاتورة وإيصال تلقائياً</small>
                </div>

                <div class="text-center mt-5">
                    <a href="{{ route('posts.featured.checkout', $post->id) }}"
                        class="btn btn-lg btn-success px-5 py-3 shadow-sm">نعم، ميز إعلاني😎</a>
                    <a href="{{ route('home') }}" class="btn btn-lg btn-secondary px-5 py-3 shadow-sm">لأ، أنشر الإعلان بدون اشتراك</a>
                </div>
            </div>
        </div>
    </div>
@endsection