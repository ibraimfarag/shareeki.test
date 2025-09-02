@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">بيانات المستخدم</h1>
        <div class="card mb-4">
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item"><strong>الاسم:</strong> {{ $user->name }}</li>
                    <li class="list-group-item"><strong>البريد الإلكتروني:</strong> {{ $user->email }}</li>
                    <li class="list-group-item"><strong>رقم الجوال:</strong> {{ $user->phone ?? '-' }}</li>
                    <li class="list-group-item"><strong>تاريخ التسجيل:</strong> {{ $user->created_at }}</li>
                  
                    <li class="list-group-item"><strong>العنوان:</strong> {{ $user->address ?? '-' }}</li>
          
                    <li class="list-group-item"><strong>عدد الإعلانات:</strong> {{ $user->posts->count() }}</li>
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-header">فرص الإعلانات الخاصة بالمستخدم</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>العنوان</th>
                                <th>تاريخ الإنشاء</th>
                                <th>مميز؟</th>
                                <th>تفاصيل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->posts as $post)
                                <tr>
                                    <td>{{ $post->id }}</td>
                                    <td>{{ $post->title }}</td>
                            
                                    <td>{{ $post->created_at }}</td>
                                    <td>
                                        @if($post->is_featured)
                                            <span class="badge bg-success">مميز</span>
                                        @else
                                            <span class="badge bg-secondary">عادي</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-sm btn-info"
                                            target="_blank">عرض</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">لا توجد إعلانات لهذا المستخدم.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection