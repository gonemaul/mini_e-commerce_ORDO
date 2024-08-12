@extends('layout.main')

@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

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
@elseif(session()->has('error'))
    <input type="hidden" id="myElement" message="{{ session('error') }}">
    <script>
        var element = document.getElementById('myElement');
        var message = element.getAttribute('message');
        createToast('error', message);
    </script>
@endif
<div class="content-wrapper">
    <div class="p-4" style="background-color: #191c24;border-radius:0.5rem">
        <div class="d-flex justify-content-between mb-3">
            <h3 class="my-auto">Orders List</h3>
        </div>
        <div class="table-responsive">
            <table id="tabel" class="display row-border hover">
                <thead>
                    <tr>
                    <th class="text-center" style="font-weight:600;"> Order ID </th>
                    <th class="text-center" style="font-weight:600;"> User </th>
                    <th class="text-center" style="font-weight:600;"> Total </th>
                    <th class="text-center" style="font-weight:600;"> Status </th>
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
                    url: '{{ route('orders.load_data') }}',
                    type: 'POST',
                    headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                paging: true,
                columns: [
                        {
                            name: 'order_id',
                            data: 'order_id',
                            orderable: false,
                            className: 'text-center'
                        },
                        {
                            name: 'name',
                            data: 'name',
                            className: 'text-center'
                        },
                        {
                            name: 'total',
                            data: 'total',
                            className: 'text-center'
                        },
                        {
                            name: 'status',
                            data: 'status',
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
