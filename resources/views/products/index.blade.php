@extends('layout.main')

@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/modal.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/alert.css') }}">

<ul class="notifications mt-3"></ul>

<script src="{{ asset('assets/js/alert.js') }}"></script>

@if(session()->has('success'))
    <script>
        var message = "{{ session()->get('success') }}";
        createToast('success', message);
    </script>
@elseif(session()->has('error'))
    <script>
        var message = "{{ session()->get('error') }}";
        createToast('error', message);
    </script>
@elseif(session()->has('alerts'))
    @foreach (session()->get('alerts') as $alert)
    <script>
        var message = "{{ $alert }}"
        createToast('error', message);
    </script>
    @endforeach
@endif

{{-- Modal Import --}}
<input type="checkbox" id="toggle" checked>
<div class="wrapper">
    <label for="toggle">
    <i class="cancel-icon fas fa-times"></i>
    </label>
    <div class="content">
      <header>Import Product</header>
      <p>Download Import Product Template if you don't have one</p>
    </div>
    <form action="{{ route('products.import') }}" method="POST" id="modal" enctype="multipart/form-data">
        @csrf
        <input type="file" class="file_up" name="file_up" accept=".xls,.xlsx" required hidden>
        <div class="button">
            <a id="upload_file" class="btn btn-primary mb-2"><i class="fa-solid fa-cloud-arrow-up"></i>Import File</a>
            <a id="templates" href="{{ route('products.templates') }}" class="btn btn-success mb-2"><i class="fa-solid fa-cloud-arrow-down"></i>Download Template</a>
        </div>
    </form>
    <div class="text">We do not share your information.</div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="content-wrapper">
        <div class="p-4" style="background-color: #191c24;border-radius:0.5rem">
            <div class="d-flex justify-content-between mb-3">
                <h3 class="my-auto">Products List</h3>
                <div class="d-block">
                    <a class="btn btn-outline-primary" href="{{ route('products.create') }}" style="font-size:1rem;font-weight:500;align-items:center;width:100%"><i class="fa-solid fa-plus"></i> Add Product</a>
                    <div class="d-flex mt-2">
                        <label for="toggle" class="btn btn-outline-success mb-0 mr-2" style="font-size:1rem;font-weight:500;align-items:center"><i class="fa-solid fa-cloud-arrow-down import"></i>Import</label>
                      <a class="btn btn-outline-warning" href="{{ route('products.export') }}" style="font-size:1rem;font-weight:500;align-items:center"><i class="fa-solid fa-cloud-arrow-up"></i>Export</a>
                    </div>
                </div>
            </div>
            {{-- @if (session()->has('failures'))
                <div class="table-responsive d-none" id="tabel_error">
                    <h4 class="text-danger">Error to import data : <small id="error_hide" class="text-primary ml-3" style="cursor: pointer">Hide</small></h4>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Row</th>
                                <th>Attribute</th>
                                <th>Error</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(session()->get('failures') as $failure)
                                <tr>
                                    <td>{{ $failure->row() }}</td>
                                    <td>{{ $failure->attribute() }}</td>
                                    <td>{{ $failure->errors()[0] }}</td>
                                    <td>{{ $failure->values()[$failure->attribute()] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table
                </div>
            @endif --}}
            <div class="table-responsive" id="tabel_data">
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
            $('#upload_file').click(function(){
                $('.file_up').trigger('click');
            })
            $('.file_up').change(function(){
                $('#modal').submit();
                $('#templates').disable();
                $('#upload_file').disable();
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
            // $('#error_show').click(function(){
            //     $('#tabel_error').show();
            //     $('#tabel_data').hide();
            // })
            // $('#error_hide').click(function(){
            //     $('#tabel_error').hide();
            //     $('#tabel_data').show();
            // })
        })
    </script>
    {{-- @if(session()->has('failures'))
        <script>
            $('#tabel_error').hide();
            // $('#tabel_data').show();
        </script>
    @endif --}}
@endsection
