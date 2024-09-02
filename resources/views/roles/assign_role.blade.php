@extends('layout.main')

@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>

<ul class="notifications mt-3"></ul>
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
        <div class="row d-flex justify-content-between pt-3">
            <div class="col-md-5">
                <h4 class="mb-5">{{ __('roles.label.add_member') }}</h4>
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
            <div class="col-md-6 ml-3 pt-0 px-5 mr-3">
                <div class="d-flex mb-5">
                    <h4>{{ __('roles.title.permissions') }}</h4>
                    @role('Super Admin')
                    <div class="d-flex ml-auto">
                        <a class="btn btn-outline-warning mr-1" href="{{ route('roles.edit', $role) }}" style="font-size:1rem;font-weight:500;align-items:center;"><i class="fa-solid fa-pen-to-square"></i>{{ __('general.edit') }}</a>
                        <form action="{{ route('roles.destroy', $role) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger" onclick="return confirm(\''.__('general.alert_delete').'\');" style="font-size:1rem;font-weight:500;align-items:center;"><i class="fa-solid fa-trash"></i>{{ __('general.delete') }}</button>
                        </form>
                    </div>
                    @endrole
                </div>
                <div class="d-flex justify-content-between">
                    <div class="left">
                        <div class="form-group pl-4">
                            <label for="">{{ __('roles.permissions.users') }}</label>
                            <div class="form-check-primary form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input check_permission" disabled {{ $permissions->contains('user_view') ? 'checked' : '' }} id="user_view"> {{ __('roles.permissions.view') }}  </label>
                            </div>
                            <div class="form-check-primary form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input check_permission" disabled {{ $permissions->contains('user_detail') ? 'checked' : '' }} id="user_detail"> {{ __('roles.permissions.view_detail') }}  </label>
                            </div>
                            <div class="form-check-primary form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input check_permission" disabled id="user_export" {{ $permissions->contains('user_export')  ? 'checked' : '' }}> {{ __('roles.permissions.export') }}  </label>
                            </div>
                            <div class="form-check-primary form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input check_permission" disabled id="assign_roles" {{ $permissions->contains('assign_roles') ? 'checked' : '' }}> {{ __('roles.permissions.assign') }} </label>
                            </div>
                        </div>
                        <div class="form-group pl-4">
                            <label for="">{{ __('roles.permissions.categories') }}</label>
                            <div class="form-check-primary form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input check_permission" disabled {{ $permissions->contains('category_view') ? 'checked' : '' }} id="category_view"> {{ __('roles.permissions.view') }}  </label>
                            </div>
                            <div class="form-check-primary form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input check_permission" disabled {{ $permissions->contains('category_create') ? 'checked' : '' }} id="category_create"> {{ __('roles.permissions.create') }}  </label>
                            </div>
                            <div class="form-check-primary form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input check_permission" disabled {{ $permissions->contains('category_edit') ? 'checked' : '' }} id="category_edit"> {{ __('roles.permissions.edit') }}  </label>
                            </div>
                            <div class="form-check-primary form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input check_permission" disabled {{ $permissions->contains('category_delete') ? 'checked' : '' }} id="category_delete"> {{ __('roles.permissions.delete') }}  </label>
                            </div>
                            <div class="form-check-primary form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input check_permission" disabled {{ $permissions->contains('category_exim') ? 'checked' : '' }} id="category_exim"> {{ __('roles.permissions.exim') }}  </label>
                            </div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="form-group pl-4">
                            <label for="">{{ __('roles.permissions.orders') }}</label>
                            <div class="form-check-primary form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input check_permission" disabled {{ $permissions->contains('order_view') ? 'checked' : '' }} id="order_view"> {{ __('roles.permissions.view') }} </label>
                            </div>
                            <div class="form-check-primary form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input check_permission" disabled {{ $permissions->contains('order_view_detail') ? 'checked' : '' }} id="order_view_detail"> {{ __('roles.permissions.view_detail') }} </label>
                            </div>
                            <div class="form-check-primary form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input check_permission" disabled {{ $permissions->contains('order_export') ? 'checked' : '' }} id="order_export"> {{ __('roles.permissions.export') }} </label>
                            </div>
                            <div class="form-check-primary form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input check_permission" disabled {{ $permissions->contains('order_update') ? 'checked' : '' }} id="order_update"> {{ __('roles.permissions.update_status') }} </label>
                            </div>
                        </div>
                        <div class="form-group pl-4">
                            <label for="">{{ __('roles.permissions.products') }}</label>
                            <div class="form-check-primary form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input check_permission" disabled {{ $permissions->contains('product_view') ? 'checked' : '' }} id="product_view"> {{ __('roles.permissions.view') }}  </label>
                            </div>
                            <div class="form-check-primary form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input check_permission" disabled {{ $permissions->contains('product_create') ? 'checked' : '' }} id="product_create"> {{ __('roles.permissions.create') }}  </label>
                            </div>
                            <div class="form-check-primary form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input check_permission" disabled {{ $permissions->contains('product_edit') ? 'checked' : '' }} id="product_edit"> {{ __('roles.permissions.edit') }}  </label>
                            </div>
                            <div class="form-check-primary form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input check_permission" disabled {{ $permissions->contains('product_delete') ? 'checked' : '' }} id="product_delete"> {{ __('roles.permissions.delete') }}  </label>
                            </div>
                            <div class="form-check-primary form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input check_permission" disabled {{ $permissions->contains('product_exim') ? 'checked' : '' }} id="product_exim"> {{ __('roles.permissions.exim') }} </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
