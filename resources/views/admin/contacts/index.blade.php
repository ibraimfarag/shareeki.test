@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">رسائل تواصل معنا</h4>
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
                                    <th scope="col">العنوان</th>
                                    <th scope="col">رقم الجوال</th>
                                    <th scope="col">البريد الإلكتروني</th>
                                    <th scope="col">النوع</th>
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
                ajax: "{{ route('contacts.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'title', name: 'title'},
                    {data: 'mobile', name: 'mobile'},
                    {data: 'email', name: 'email'},
                    {data: 'sort', name: 'sort'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                dom: 'lBfrtip',
            });
            
        });
        $.fn.dataTable.ext.errMode = 'none';
    </script>
    
@endsection