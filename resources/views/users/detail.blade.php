@extends('layout.main')

@section('content')
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>
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
                    <span class="col-sm-9">: {{ $user->is_admin ? 'Admin' : 'User' }}</span>
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
            @if($orders->isNotEmpty())
                <h3 class="pb-2" style="border-bottom: 2px solid #B1ADD4;">List Orders</h3>
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
                            <span class="d-block">Waktu Pemesanan</span>
                            <span class="text-muted">{{ $order->created_at }}</span>
                        </div>
                        <a href="{{ route('orders.detail', $order->id) }}" class="btn btn-primary my-auto mr-3"><i class="fa-solid fa-eye"></i>Detail</a>
                    </div>
                @endforeach
            @else
                <h4 class="text-center">Order Not Available !!</h4>
            @endif
        </div>
    </div>
@endsection
