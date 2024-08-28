@extends('layout.main')

@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>

<ul class="notifications"></ul>
<link rel="stylesheet" href="{{ asset('assets/css/alert.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
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
    <div class="p-4 mb-3" style="background-color: #191c24;border-radius:0.5rem">
        <div class="d-flex justify-content-between mb-3">
            <h3 class="my-auto">{{ __('roles.label.add_member') }}</h3>
            @role('Super Admin')
            <div class="d-block">
                <a class="btn btn-outline-warning" href="{{ route('roles.edit', $role) }}" style="font-size:1rem;font-weight:500;align-items:center;width:100%"><i class="fa-solid fa-pen-to-square"></i> {{ __('roles.label.edit_role') }}</a>
            </div>
            @endrole
        </div>
        <div class="col-md-6">
            <form action="{{ route('roles.assign', $role) }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="member">{{ __('roles.label.email') }} <span class="text-danger">*</span></label>
                    <select class="form-control" id="member" name="member" value="{{ old('member') }}">
                        <option value="" selected>Selected</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" >{{ $user->email }}</option>
                    @endforeach
                    </select>
                </div>
                <button class="btn btn-primary" type="submit">{{ __('roles.label.add_member') }}</button>
            </form>
        </div>
    </div>
    <div class="p-4" style="background-color: #191c24;border-radius:0.5rem">
        <div class="d-flex justify-content-between mb-3">
            <h3 class="my-auto">{{ __('roles.label.member_list') }}</h3>
        </div>
        <div class="table-responsive">
            <table class="display hover row-border" id="tabel">
                <thead>
                  <tr>
                    <th class="text-center" style="font-weight:600;"> Profile </th>
                    <th class="text-center" style="font-weight:600;"> {{ __('general.name') }} </th>
                    <th class="text-center" style="font-weight:600;"> Email </th>
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
                    url: '{{ route('roles.load_data', $role) }}',
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
