@extends('layout.main')

@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>

<link rel="stylesheet" href="{{ asset('assets/css/alert.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
<ul class="notifications"></ul>
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
@endif

<style>
    .dropdown-item:hover{
        color: #ffff
    }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="content-wrapper">
    <div class="p-4" style="background-color: #191c24;border-radius:0.5rem">
        <div class="d-flex justify-content-between mb-3">
            <h3 class="my-auto">{{ __('roles.title.list') }}</h3>
        </div>
        <div class="table-responsive">
            <table class="display hover row-border" id="tabel">
                <thead>
                  <tr>
                    <th class="text-center" style="font-weight:600;"> No </th>
                    <th class="text-center" style="font-weight:600;"> {{ __('roles.label.name') }} </th>
                    <th class="text-center" style="font-weight:600;"> {{ __('roles.label.member') }} </th>
                    <th class="text-center" style="font-weight:600;"> {{ __('general.action') }} </th>
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
                    url: '{{ route('roles.load') }}',
                    type: 'Post',
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
                            orderable: true,
                            className: 'text-center'
                        },
                        {
                            name: 'member',
                            data: 'member',
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
