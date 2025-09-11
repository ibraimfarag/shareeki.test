<div class="row">
    <div class="col-md-12">
        <h4>{{ $post->title }}</h4>
        <hr>

        <div class="row">
            <div class="col-md-6">
                <strong>الرقم:</strong> {{ $post->id }}
            </div>
            <div class="col-md-6">
                <strong>الكود:</strong> {{ $post->code }}
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-6">
                <strong>تاريخ الإنشاء:</strong> {{ $post->created_at->format('Y-m-d H:i') }}
            </div>
            <div class="col-md-6">
                <strong>آخر تحديث:</strong> {{ $post->updated_at->format('Y-m-d H:i') }}
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-6">
                <strong>حالة التمييز:</strong>
                <span class="badge {{ $post->is_featured ? 'badge-success' : 'badge-secondary' }}">
                    {{ $post->is_featured ? 'مميز' : 'غير مميز' }}
                </span>
            </div>
            <div class="col-md-6">
                <strong>تاريخ انتهاء التمييز:</strong>
                {{ $post->featured_until ? $post->featured_until->format('Y-m-d H:i') : '-' }}
            </div>
        </div>

        @if($post->description)
            <div class="row mt-3">
                <div class="col-md-12">
                    <strong>الوصف:</strong>
                    <div class="border p-2 mt-1">
                        {!! nl2br(e($post->description)) !!}
                    </div>
                </div>
            </div>
        @endif

        @if($post->img)
            <div class="row mt-3">
                <div class="col-md-12">
                    <strong>الصورة الرئيسية:</strong>
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $post->img) }}" alt="{{ $post->title }}" class="img-thumbnail"
                            style="max-width: 300px;">
                    </div>
                </div>
            </div>
        @endif

        <div class="row mt-3">
            <div class="col-md-6">
                <strong>السعر:</strong> {{ $post->price }} {{ $post->currency ?? 'ريال' }}
            </div>
            <div class="col-md-6">
                <strong>الحالة:</strong>
                <span class="badge {{ $post->blacklist ? 'badge-danger' : 'badge-success' }}">
                    {{ $post->blacklist ? 'محظور' : 'نشط' }}
                </span>
            </div>
        </div>
    </div>
</div>