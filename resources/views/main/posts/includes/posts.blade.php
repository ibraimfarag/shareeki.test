@foreach ($posts as $post)
    <div class="col-lg-3 col-md-6 col-sm-6 mt-2">
        <a href="{{ route('the_posts.show', $post->id) }}" class="w-100 d-flex">
            <div class="card box-shadow-medium border-radius-medium card-hover w-100">
                <img
                    src="{{ !empty($post->img) ? $post->img_path : ($post->category->img_path ?? asset('storage/main/categories/default.jpg')) }}"
                    class="card-img-top" alt="...">
                <div class="card-body">
                    <h4 class="h4 card-title text-dark-heading mb-3 line-clamp2">{{ $post->category->name ?? 'غير محدد' }}</h4>
                    <h3 class="h4 card-text text-dark-content mb-3 line-clamp2" style="word-wrap: break-word;">{{ $post->title }}</h3>
                </div>
                <div class="card-footer bg-transparent price-footer">
                    <h5 class="h4 text-blue-light-heading mb-0 text-wrap">المبلغ المطلوب {{ number_format($post->price) }} ريال</h5>
                </div>
            </div>
        </a>
    </div>
@endforeach
  @if($posts->hasPages())
  <div class="w-100 mt-4">
        <div class="d-flex justify-content-center">
            {{-- Use bootstrap-4 pagination template to keep consistent markup (links() already outputs the <ul>) --}}
            {{ $posts->links('pagination::bootstrap-4') }}
        </div>
  </div>
  @endif
