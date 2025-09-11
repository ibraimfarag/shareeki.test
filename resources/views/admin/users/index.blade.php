@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        مستخدمين الموقع
                        <a href="{{ route('users.create') }}" class="btn btn-primary" style="float:left">إضافة مستخدم
                            جديد</a>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <table class="table table-bordered data-table">

                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">الإسم</th>
                                    <th scope="col">البريد الإلكتروني</th>
                                    <th scope="col">مدينة السكن</th>
                                    <th scope="col">الجوال</th>
                                    <th scope="col">تاريخ الميلاد</th>
                                    <th scope="col">الميزانية القصوى</th>
                                    <th scope="col">حالة التفعيل</th>
                                    <th scope="col">العمليات</th>
                                    <th scope="col">العمليات</th>
                                </tr>
                            </thead>

                        </table>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript">
        $(function () {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'city', name: 'city' },
                    { data: 'phone', name: 'phone' },
                    { data: 'birth_date', name: 'birth_date' },
                    { data: 'max_budget', name: 'max_budget' },
                    { data: 'email_verified_at', name: 'email_verified_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                    { data: 'actionone', name: 'actionone', orderable: false, searchable: false },
                ],
                dom: 'lBfrtip',
            });

        });
        $.fn.dataTable.ext.errMode = 'none';
    </script>
@endsection