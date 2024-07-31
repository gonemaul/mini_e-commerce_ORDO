@extends('layout.main')

@section('content')
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
                <h3 class="mb-4">General Account</h3>
                <div class="row mb-1">
                    <strong class="col-sm-3">Name</strong>
                    <span class="col-sm-9">: {{ $user->name }}</span>
                </div>
                <div class="row mb-1">
                    <strong class="col-sm-3">Email</strong>
                    <span class="col-sm-9">: {{ $user->email }}</span>
                </div>
                <div class="row mb-1">
                    <strong class="col-sm-3">Role</strong>
                    <span class="col-sm-9">: {{ $user->is_admin ? 'Administrator' : 'User' }}</span>
                </div>
                <div class="row mb-1">
                    <strong class="col-sm-3">Last login</strong>
                    <span class="col-sm-9">: {{ $user->last_login }}</span>
                </div>
                <div class="row mb-1">
                    <strong class="col-sm-3">Joined</strong>
                    <span class="col-sm-9">: {{ $user->created_at->diffForHumans() }}</span>
                </div>
                <div class="row mb-1">
                    <strong class="col-sm-3">Total Order</strong>
                    <span class="col-sm-9">: {{ count($user->orders) }}</span>
                </div>
                <a href="{{ route('users.list') }}" class="btn btn-primary mt-2">Kembali</a>
            </div>
        </div>
        <div class="p-4" style="background-color: #191c24;border-radius:0.5rem">
            {{-- <h3>List Orders</h3> --}}
            <h4 class="text-center">Order Not Available !!</h4>
        </div>
    </div>
@endsection
