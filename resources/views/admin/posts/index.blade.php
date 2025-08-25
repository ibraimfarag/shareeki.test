@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h3 class="card-header">
                   الإعلانات والعروض 
                   <a href="{{ route('posts.create') }}" class="btn btn-primary" style="float:left">إضافة إعلان جديد</a>
                </h3>

                <div class="card-body">
                    @if(session()->has('message'))
                        <div class="alert {{session('alert') ?? 'alert-info'}}" style="background:green;">
                            {{ session('message') }}
                        </div>
                    @endif

                    <table class="table table-bordered data-table">

                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">الإسم</th>
                                <th scope="col">العمليات</th>
                                <th scope="col">العمليات</th>
                                <th scope="col">العمليات</th>
                                <th scope="col">العمليات</th>
                            </tr>
                        </thead>
                          
                    </table>
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
                ajax: "{{ route('posts.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'title', name: 'title'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                    {data: 'actionone', name: 'actionone', orderable: false, searchable: false},
                    {data: 'actiontwo', name: 'actiontwo', orderable: false, searchable: false},
                    {data: 'actionthree', name: 'actionthree', orderable: false, searchable: false}
                ],
                dom: 'lBfrtip',
            });
        });
        $.fn.dataTable.ext.errMode = 'none';
    </script>
@endsection

