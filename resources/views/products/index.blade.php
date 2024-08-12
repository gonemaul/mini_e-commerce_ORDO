@extends('layout.main')

@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">

<ul class="notifications"></ul>

<link rel="stylesheet" href="{{ asset('assets/css/alert.css') }}">
<script src="{{ asset('assets/js/alert.js') }}"></script>
@if(session()->has('success'))
    <input type="hidden" id="myElement" message="{{ session('success') }}">
    <script>
        var element = document.getElementById('myElement');
        var message = element.getAttribute('message');
        createToast('success', message);
    </script>
@endif

<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="content-wrapper">
        <div class="p-4" style="background-color: #191c24;border-radius:0.5rem">
            <div class="d-flex justify-content-between mb-3">
                <h3 class="my-auto">Products List</h3>
                <a class="btn btn-outline-primary btn-icon-text py-auto text-center" href="{{ Route('products.create') }}" style="font-size:1rem;font-weight:500"><i class="fa-solid fa-plus"></i> Add Product</a>
            </div>
            <div class="table-responsive">
                <table id="tabel" class="display hover row-border">
                    <thead>
                      <tr>
                        <th class="text-center" style="font-weight:600;"> No </th>
                        <th class="text-center" style="font-weight:600;"> Product Name </th>
                        <th class="text-center" style="font-weight:600;"> Category </th>
                        <th class="text-center" style="font-weight:600;"> Price </th>
                        <th class="text-center" style="font-weight:600;"> Stock </th>
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
                    url: '{{ route('products.load_data') }}',
                    type: 'POST',
                    headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                paging: true,
                columns: [
                        {
                            name: 'no',
                            data: 'DT_RowIndex',
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
                            name: 'category',
                            data: 'category',
                            className: 'text-center'
                        },
                        {
                            name: 'price',
                            data: 'price',
                            className: 'text-center'
                        },
                        {
                            name: 'stock',
                            data: 'stock',
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
