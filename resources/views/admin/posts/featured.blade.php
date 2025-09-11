@extends('admin.layouts.app')
@section('content')
    <div class="container">
        <h2 class="mb-4">الإعلانات المميزة الحالية</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>العنوان</th>
                    <th>تاريخ الإنشاء</th>
                    <th>تاريخ انتهاء التمييز</th>
                    <th>الحالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($featuredPosts as $post)
                    <tr>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->created_at }}</td>
                        <td>{{ $post->featured_until ?? '-' }}</td>
                        <td>{{ $post->is_featured ? 'مميز' : 'غير مميز' }}</td>
                        <td>
                            <button type="button" class="btn btn-info btn-sm show-post-btn"
                                data-slug="{{ $post->slug }}">عرض</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">لا توجد إعلانات مميزة حالياً</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="postModal" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="postModalLabel">تفاصيل الإعلان</h5>
                    <button type="button" class="close close-modal-btn" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalContent">
                    <div class="text-center">جاري التحميل...</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-modal-btn">إغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // عند الضغط على زر العرض
            $('.show-post-btn').on('click', function () {
                var postSlug = $(this).data('slug');
                var url = '/admin/posts/' + postSlug + '/modal-show';

                $('#postModalLabel').text('عرض الإعلان');
                $('#modalContent').html('<div class="text-center">جاري التحميل...</div>');
                $('#postModal').modal('show');

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (data) {
                        $('#modalContent').html(data);
                    },
                    error: function () {
                        $('#modalContent').html('<div class="text-danger">حدث خطأ أثناء جلب البيانات</div>');
                    }
                });
            });

            // معالجة إغلاق المودال
            $('.close-modal-btn').on('click', function() {
                $('#postModal').modal('hide');
            });

            // إغلاق المودال عند الضغط على الخلفية
            $('#postModal').on('click', function(e) {
                if (e.target === this) {
                    $('#postModal').modal('hide');
                }
            });

            // إغلاق المودال عند الضغط على Escape
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $('#postModal').hasClass('show')) {
                    $('#postModal').modal('hide');
                }
            });
        });
    </script>
@endsection