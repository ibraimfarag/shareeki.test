  @foreach ($posts as $post)
            <div class="col-lg-3 mt-2">
                <a href="{{ route('the_posts.show', $post->id) }}">
                    <div class="card box-shadow-medium border-radius-medium card-hover">
                    <img
                        src="{{ !empty($post->img) ? $post->img_path : ($post->category->img_path ?? asset('storage/main/categories/default.jpg')) }}"
                        class="card-img-top" alt="...">
                    <div class="card-body">
                        <h4 class="h4 card-title text-dark-heading mb-2 line-clamp2">{{ $post->category->name ?? 'غير محدد' }}</h4>
                        <h3 class="h4 card-text text-dark-content mb-0 line-clamp2">{{ $post->title }}</h3>
                    </div>
                    <div class="card-footer bg-transparent">
                        <h5 class="h4 text-blue-light-heading">المبلغ المطلوب {{ number_format($post->price) }} ريال</h5>
                    </div>
                    </div>
                </a>
         </div>
  @endforeach
  @if(count($posts) > 0)
  <div class="container mt-4">
        <div class="d-flex justify-content-center">
        <nav aria-label="Page navigation example">
            <ul class="pagination">

                {{ $posts->render() }}

            </ul>
        </nav>
        </div>
  </div>
  @endif
