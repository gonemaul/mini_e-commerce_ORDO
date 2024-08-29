@extends('layout.main')

@section('content')
{{-- CDN --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>

{{-- Local style --}}
<link rel="stylesheet" href="{{ asset('assets/css/modal.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/alert.css') }}">

<ul class="notifications mt-3"></ul>
<script src="{{ asset('assets/js/alert.js') }}"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- Alert --}}
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

{{-- Modal Import --}}
<input type="checkbox" id="toggle" checked>
<input type="hidden" id="permissions" value="{{ old('permissions', json_encode($permissions)) }}">
<input type="hidden" id="user" value="{{ $user->id }}">
<div class="wrapper permis">
    <label for="toggle">
    <i class="cancel-icon fas fa-times"></i>
    </label>
    <div class="top">
        <p>Permission</p>
    </div>
    <div id="modal">
        @csrf
        <div class="d-flex justify-content-between">
            <div class="left">
                <div class="form-group pl-4">
                    <label class="head" for="">{{ __('roles.permissions.users') }}</label>
                    <div class="form-check-primary form-check">
                        <label class="form-check-label">
                        <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('user_view') ? 'checked' : '' }} id="user_view"> {{ __('roles.permissions.view') }}  </label>
                    </div>
                    <div class="form-check-primary form-check">
                        <label class="form-check-label">
                        <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('user_detail') ? 'checked' : '' }} id="user_detail"> {{ __('roles.permissions.view_detail') }}  </label>
                    </div>
                    <div class="form-check-primary form-check">
                        <label class="form-check-label">
                        <input type="checkbox" class="form-check-input check_permission" id="user_export" {{ $permissions->contains('user_export')  ? 'checked' : '' }}> {{ __('roles.permissions.export') }}  </label>
                    </div>
                    <div class="form-check-primary form-check">
                        <label class="form-check-label">
                        <input type="checkbox" class="form-check-input check_permission" id="assign_roles" {{ $permissions->contains('assign_roles') ? 'checked' : '' }}> {{ __('roles.permissions.assign') }} </label>
                    </div>
                </div>
                <div class="form-group pl-4 mt-3">
                    <label class="head" for="">{{ __('roles.permissions.categories') }}</label>
                    <div class="form-check-primary form-check">
                        <label class="form-check-label">
                        <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('category_view') ? 'checked' : '' }} id="category_view"> {{ __('roles.permissions.view') }}  </label>
                    </div>
                    <div class="form-check-primary form-check">
                        <label class="form-check-label">
                        <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('category_create') ? 'checked' : '' }} id="category_create"> {{ __('roles.permissions.create') }}  </label>
                    </div>
                    <div class="form-check-primary form-check">
                        <label class="form-check-label">
                        <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('category_edit') ? 'checked' : '' }} id="category_edit"> {{ __('roles.permissions.edit') }}  </label>
                    </div>
                    <div class="form-check-primary form-check">
                        <label class="form-check-label">
                        <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('category_delete') ? 'checked' : '' }} id="category_delete"> {{ __('roles.permissions.delete') }}  </label>
                    </div>
                    <div class="form-check-primary form-check">
                        <label class="form-check-label">
                        <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('category_exim') ? 'checked' : '' }} id="category_exim"> {{ __('roles.permissions.exim') }}  </label>
                    </div>
                </div>
            </div>
            <div class="right">
                <div class="form-group pl-4">
                    <label class="head" for="">{{ __('roles.permissions.orders') }}</label>
                    <div class="form-check-primary form-check">
                        <label class="form-check-label">
                        <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('order_view') ? 'checked' : '' }} id="order_view"> {{ __('roles.permissions.view') }} </label>
                    </div>
                    <div class="form-check-primary form-check">
                        <label class="form-check-label">
                        <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('order_view_detail') ? 'checked' : '' }} id="order_view_detail"> {{ __('roles.permissions.view_detail') }} </label>
                    </div>
                    <div class="form-check-primary form-check">
                        <label class="form-check-label">
                        <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('order_export') ? 'checked' : '' }} id="order_export"> {{ __('roles.permissions.export') }} </label>
                    </div>
                    <div class="form-check-primary form-check">
                        <label class="form-check-label">
                        <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('order_update') ? 'checked' : '' }} id="order_update"> {{ __('roles.permissions.update_status') }} </label>
                    </div>
                </div>
                <div class="form-group pl-4 mt-3">
                    <label class="head" for="">{{ __('roles.permissions.products') }}</label>
                    <div class="form-check-primary form-check">
                        <label class="form-check-label">
                        <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('product_view') ? 'checked' : '' }} id="product_view"> {{ __('roles.permissions.view') }}  </label>
                    </div>
                    <div class="form-check-primary form-check">
                        <label class="form-check-label">
                        <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('product_create') ? 'checked' : '' }} id="product_create"> {{ __('roles.permissions.create') }}  </label>
                    </div>
                    <div class="form-check-primary form-check">
                        <label class="form-check-label">
                        <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('product_edit') ? 'checked' : '' }} id="product_edit"> {{ __('roles.permissions.edit') }}  </label>
                    </div>
                    <div class="form-check-primary form-check">
                        <label class="form-check-label">
                        <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('product_delete') ? 'checked' : '' }} id="product_delete"> {{ __('roles.permissions.delete') }}  </label>
                    </div>
                    <div class="form-check-primary form-check">
                        <label class="form-check-label">
                        <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('product_exim') ? 'checked' : '' }} id="product_exim"> {{ __('roles.permissions.exim') }} </label>
                    </div>
                </div>
            </div>
        </div>
        <button class="btn btn-primary mt-3" id="submit">{{ __('general.save') }}</button>
    </div>
</div>

<div class="content-wrapper">
    <div class="p-4 mb-3 row mx-0" style="background-color: #191c24;border-radius:0.5rem">
        <div class="profile col-md-6 align-content-center pl-5">
            @if ($user->profile_image)
                <img class="rounded-circle" style="width: 130px;height:130px" src="{{ asset('storage/' . $user->profile_image) }}">
            @else
                <img class="rounded-circle" style="width: 100px;height:100px" src="https://ui-avatars.com/api/?name={{ $user->name }}&color=7F9CF5&background=EBF4FF">
            @endif
        </div>
        <div class="profile-info col-md-6">
            <h3 class="mb-4">{{ __('user.title_detail') }}</h3>
            <div class="row mb-2">
                <strong class="col-sm-3">{{ __('general.name') }}</strong>
                <span class="col-sm-9">: {{ $user->name }}</span>
            </div>
            <div class="row mb-2">
                <strong class="col-sm-3">Email</strong>
                <span class="col-sm-9">: {{ $user->email }}</span>
            </div>
            <div class="row mb-2">
                <strong class="col-sm-3">{{ __('general.type') }}</strong>
                <span class="col-sm-9">: {{ $user->is_admin ? 'Web' : 'Api' }}</span>
            </div>
            @if($user->is_admin)
                <div class="row mb-2">
                    <strong class="col-sm-3">{{ __('general.role') }}</strong>
                    <span class="col-sm-9">: {{ $user->getRoleNames()[0] ?? 'Default'}}</span>
                </div>
            @endif
            <div class="row mb-2">
                <strong class="col-sm-3">{{ __('user.last_login') }}</strong>
                <span class="col-sm-9">: {{ $user->last_login }}</span>
            </div>
            <div class="row mb-2">
                <strong class="col-sm-3">{{ __('general.join') }}</strong>
                <span class="col-sm-9">: {{ $user->created_at->diffForHumans() }}</span>
            </div>
            @if($user->is_admin == false)
                <div class="row mb-2">
                    <strong class="col-sm-3">Total Order</strong>
                    <span class="col-sm-9">: {{ count($user->orders) }}</span>
                </div>
            @endif
            <a href="{{ route('users.list') }}" class="btn btn-primary mt-2"><i class="fa-solid fa-arrow-left"></i>{{ __('general.back') }}</a>
            @can('assign_roles')
                @if(!$user->hasRole('Super Admin') && $user->id != Auth::user()->id && $user->is_admin)
                <label for="toggle" class="btn btn-warning mt-2 mb-0"><i class="mdi mdi-account-key"></i> {{ __('general.account.access') }}</label>
                    {{-- <a href="{{ route('roles.show', $user->Roles->first()->id ?? '') }}" class="btn btn-warning mt-2"><i class="mdi mdi-account-key"></i> {{ __('general.account.access') }}</a> --}}
                @endif
            @endcan
        </div>
    </div>
    @if($user->is_admin == false)
    <div class="p-4" style="background-color: #191c24;border-radius:0.5rem">
        @if($orders->isNotEmpty())
            <h3 class="pb-2" style="border-bottom: 2px solid #B1ADD4;">{{ __('user.list') }}</h3>
            @foreach ($orders as $order)
                <div class="item mb-3 p-2 d-flex justify-content-between" style="border-bottom: 2px dashed #B1ADD4;">
                    <div class="id">
                        <span class="d-block">Order ID</span>
                        <span class="text-muted">#{{ $order->order_id }}</span>
                    </div>
                    <div class="status">
                        <span class="d-block">Status</span>
                        <span class="text-muted">{{ $order->status }}</span>
                    </div>
                    <div class="total">
                        <span class="d-block">Total</span>
                        <span class="text-muted">Rp. {{ number_format($order->total) }}</span>
                    </div>
                    <div class="status">
                        <span class="d-block">Barang</span>
                        <span class="text-muted">{{ $order->orderItems->sum('quantity') }}</span>
                    </div>
                    <div class="status">
                        <span class="d-block">{{ __('user.date_booking') }}</span>
                        <span class="text-muted">{{ $order->created_at }}</span>
                    </div>
                    <a href="{{ route('orders.detail', $order->id) }}" class="btn btn-primary my-auto mr-3"><i class="fa-solid fa-eye"></i>Detail</a>
                </div>
            @endforeach
        @else
            <h4 class="text-center">{{ __('general.no_data') }}</h4>
        @endif
    </div>
    @endif
</div>
<script>
    var permissions = [];
    // var permis = [];
    const sumbit = $('#submit')
    permissions = JSON.parse($('#permissions').val());

    $('.check_permission').change(function() {
        var permission = $(this).attr('id');

        if ($(this).is(':checked')) {
            if (!permissions.includes(permission)) {
                permissions.push(permission);
            }
        } else {
            var index = permissions.indexOf(permission);
            if (index !== -1) {
                permissions.splice(index, 1);
            }
        }
        // console.log(permissions);
    })

    function checkPermissions(permis){
        $('.check_permission').each(function() {
            if (permis.includes($(this).attr('id'))) {
                $(this).prop('checked', true);
            } else {
                $(this).prop('checked', false);
            }
        });
    }

    $('#submit').click(function(){
        var data = JSON.stringify(permissions)
        var user = $('#user').val();
            $.ajax({
                type: 'POST',
                    url: '/roles/assign/permis',
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                data:{
                    user_id: user,
                    data_permis: data
                },
                success: function(response){
                    permis = response.permis;
                    checkPermissions(permis);
                    $('#toggle').prop('checked', true);
                    createToast('success', response.pesan);
                },
                error: function (error){
                    createToast('error', error);
                }
            })
            // console.log(permissions);
    })
</script>
@endsection
