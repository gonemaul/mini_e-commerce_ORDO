@extends('layout.main')

@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .dropdown-item:hover{
        color: #ffff
    }
</style>
<div class="content-wrapper">
    <div class="col-lg-12 p-4" style="background-color: #191c24;border-radius:0.5rem">
        <div class="d-flex justify-content-between mb-3">
            <h3 class="my-auto">Users List</h3>
            <div class="dropdown">
                <button class="btn btn-outline-warning dropdown-toggle" type="button" id="dropdownMenuIconButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa-solid fa-cloud-arrow-up"></i>Export
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuIconButton1">
                  <h6 class="dropdown-header">Export</h6>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="{{ route('users.export') }}">Admin</a>
                  <a class="dropdown-item" href="{{ route('users.export_customer') }}">Customer</a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table id="tabel" class="display hover row-border">
                <thead>
                <tr>
                    <th class="text-center" style="font-weight:600;"> Profile </th>
                    <th class="text-center" style="font-weight:600;"> Name </th>
                    <th class="text-center" style="font-weight:600;"> Email </th>
                    <th class="text-center" style="font-weight:600;"> Role </th>
                    <th class="text-center" style="font-weight:600;"> Action </th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="{{ asset('assets/vendors/DataTables/datatables.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let table = new DataTable('#tabel', {
                prosessing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('users.load_data') }}',
                    type: 'POST',
                    headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                paging: true,
                columns: [
                        {
                            name: 'profile',
                            data: 'profile',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            name: 'name',
                            data: 'name',
                            orderable: true
                        },
                        {
                            name: 'email',
                            data: 'email',
                        },
                        {
                            name: 'role',
                            data: 'role',
                            className: 'text-center'
                        },
                        {
                            name: 'Action',
                            data: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'justify-content-center d-flex'
                        }
                ]
        });
    })
</script>
@endsection
