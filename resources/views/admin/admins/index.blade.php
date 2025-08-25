@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    مديرين الموقع
                    <a href="{{ route('admins.create') }}" class="btn btn-primary" style="float:left">إضافة مدير جديد</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-striped table-bordered">

                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">الإسم</th>
                                    <th scope="col">البريد الإلكتروني</th>
                                    <!--<th scope="col">الصورة</th>!-->
                                    <th scope="col">العمليات</th>
                                    <th scope="col">العمليات</th>
                                </tr>
                            </thead>
                            @forelse ($admins as $admin)
                            <tbody>
                                <tr>
                                    <td scope="row">#</td>
                                    <td>{{ $admin->user_name  }}</td>
                                    <td>{{ $admin->email }}</td>
                                    {{--<td><img src="{{ $admin->img_path }}" alt="" srcset=""></td>--}}
                                    <td>
                                        {{--<a href="{{ route('admins.show', $admin->user_name) }}" class="btn btn-primary">Show</a>--}}
                                        <a href="{{ route('admins.edit', $admin->user_name) }}" class="btn btn-warning">تعديل</a>
                                    </td>
                                    <td>
                                        <form action="{{ route('admins.destroy', $admin->user_name) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger" type="submit">حذف</button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                            @empty
                                <li class="list-group-item">
                                    No admins Added
                                </li>
                            @endforelse
                        </table>
                       
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection