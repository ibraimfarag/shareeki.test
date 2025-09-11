<form id="editPostForm" method="POST" action="{{ route('posts.update', $post->slug) }}" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="title">العنوان <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $post->title }}" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="category_id">الفئة <span class="text-danger">*</span></label>
                <select class="form-control" id="category_id" name="category_id" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="sort">نوع الفرصة <span class="text-danger">*</span></label>
                <select class="form-control" id="sort" name="sort" required>
                    <option value="فكرة" {{ $post->sort == 'فكرة' ? 'selected' : '' }}>فكرة</option>
                    <option value="عمل قائم" {{ $post->sort == 'عمل قائم' ? 'selected' : '' }}>عمل قائم</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="body">وصف الفرصة <span class="text-danger">*</span></label>
                <textarea class="form-control" id="body" name="body" rows="4" required>{{ $post->body }}</textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="price">السعر <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="price" name="price" value="{{ $post->price }}" step="0.01"
                    required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="currency">العملة</label>
                <select class="form-control" id="currency" name="currency">
                    <option value="ريال" {{ $post->currency == 'ريال' ? 'selected' : '' }}>ريال سعودي</option>
                    <option value="دولار" {{ $post->currency == 'دولار' ? 'selected' : '' }}>دولار أمريكي</option>
                    <option value="يورو" {{ $post->currency == 'يورو' ? 'selected' : '' }}>يورو</option>
                </select>
            </div>
        </div>
    </div>

    <!-- إضافة الحقول المخفية للقيم المطلوبة -->
    <input type="hidden" name="area_id" value="{{ $post->area_id }}">
    <input type="hidden" name="partners_no" value="{{ $post->partners_no ?? 1 }}">
    <input type="hidden" name="partner_sort" value="{{ $post->partner_sort ?? '0' }}">
    <input type="hidden" name="the_tags" value="">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ $post->is_featured ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_featured">
                        إعلان مميز
                    </label>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="featured_until">تاريخ انتهاء التمييز</label>
                <input type="datetime-local" class="form-control" id="featured_until" name="featured_until"
                    value="{{ $post->featured_until ? $post->featured_until->format('Y-m-d\TH:i') : '' }}">
            </div>
        </div>
    </div>

    @if($post->img)
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>الصورة الحالية</label>
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $post->img) }}" alt="{{ $post->title }}" class="img-thumbnail"
                            style="max-width: 200px;">
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="main_image">تغيير الصورة الرئيسية</label>
                <input type="file" class="form-control-file" id="main_image" name="main_image" accept="image/*">
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        // تأكد من أن النموذج موجود قبل إضافة event listener
        $(document).on('submit', '#editPostForm', function (e) {
            e.preventDefault();
            console.log('Form submission intercepted');

            var formData = new FormData(this);
            var actionUrl = $(this).attr('action');

            console.log('Submitting to:', actionUrl);

            // إضافة مؤشر تحميل
            var submitBtn = $(this).find('button[type="submit"]');
            var originalText = submitBtn.text();
            submitBtn.prop('disabled', true).text('جاري الحفظ...');

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    console.log('Success:', response);
                    $('#postModal').modal('hide');
                    // إعادة تحميل الصفحة لإظهار التغييرات
                    window.location.reload();
                },
                error: function (xhr, status, error) {
                    console.log('Error:', xhr.responseText);
                    alert('حدث خطأ أثناء حفظ البيانات: ' + error);
                },
                complete: function () {
                    // إعادة تفعيل الزر
                    submitBtn.prop('disabled', false).text(originalText);
                }
            });

            return false; // منع الإرسال العادي للنموذج
        });
    });
</script>