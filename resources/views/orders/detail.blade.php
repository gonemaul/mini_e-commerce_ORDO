@extends('layout.main')

@section('content')
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>
<div class="content-wrapper">
    <div class="p-4" style="background-color: #191c24;border-radius:0.5rem">
        @switch($order->status)
            @case('Success')
                <div class="status bg-success p-3 mb-3 text-center" style="border-radius: 0.5rem;font-size:1rem;font-weight:600">
                    <span>{{ $order->status }}...</span>
                </div>
                @break
            @case('Pending')
                <div class="status bg-warning p-3 mb-3 text-center" style="border-radius: 0.5rem;font-size:1rem;font-weight:600">
                    <span>{{ $order->status }}...</span>
                </div>
                @break
            @case('Failed')
                <div class="status bg-danger p-3 mb-3 text-center" style="border-radius: 0.5rem;font-size:1rem;font-weight:600">
                    <span>{{ $order->status }}...</span>
                </div>
                @break
            @case('Expired')
                <div class="status bg-info p-3 mb-3 text-center" style="border-radius: 0.5rem;font-size:1rem;font-weight:600">
                    <span>{{ $order->status }}...</span>
                </div>
                @break
            @case('Canceled')
                <div class="status bg-danger p-3 mb-3 text-center" style="border-radius: 0.5rem;font-size:1rem;font-weight:600">
                    <span>{{ $order->status }}...</span>
                </div>
                @break
            @default
                <div class="status bg-secondary p-3 mb-3 text-center" style="border-radius: 0.5rem;font-size:1rem;font-weight:600">
                    <span>{{ $order->status }}...</span>
                </div>
        @endswitch
        <div class="info p-3 mb-3 d-flex justify-content-between">
            <div class="user d-flex">
                <div class="left mr-3">
                    <img class="rounded-circle" src="https://ui-avatars.com/api/?name={{ $order->name }}&color=7F9CF5&background=EBF4FF"> </td>
                </div>
                <div class="right">
                    <p class="m-0">
                        <span>{{ $order->name }}</span>
                    </p>
                    <p class="m-0">
                        <span>{{ $order->phone }}</span> |
                        <span>{{ $order->email }}</span>
                    </p>
                    <p class="m-0">
                        <span>{{ $order->address }}</span>
                        <span>, {{ $order->city }}</span>
                        <span>{{ $order->postal_code }}</span>
                    </p>
                </div>
            </div>
            <div class="id">
                <span class="d-block">Order ID</span>
                <span>#{{ $order->order_id }}</span>
            </div>
        </div>
        <div class="product-item ">
            @foreach ($order->orderItems as $index => $item)
                <div class="row mb-3 p-3 m-0" style="border-radius:0.5rem;border: 1px solid #8c8c8c;color: ">
                    <div class="col-md-12 d-flex justify-content-between" style="width: 100%">
                        <div class="detail-left d-flex">
                            <div class="name ml-4">
                                <h4>{{ $item->product_name }}</h4>
                                <label for="" class="badge badge-outline-primary">{{ $item->category_name }}</label>
                            </div>
                        </div>
                        <div class="info-right pt-3">
                            <span class="text-muted">{{ $item->quantity }} x</span>
                            <div class="text-end">
                                Rp. {{ number_format($item->price) }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="info-bottom d-flex justify-content-between p-3 mt-4" style="border: 2px dashed #B1ADD4;border-radius:0.5rem">
            <div class="left">
                <div class="mb-2 text-muted">
                    <span class="d-block">Waktu pemesanan</span>
                    <span>{{ $order->created_at }}</span>
                </div>
                <div class="text-muted">
                    <span class="d-block">Waktu pembayaran</span>
                    <span>{{ $order->updated_at }}</span>
                </div>
            </div>
            <div class="right d-flex align-content-center justify-content-between col-md-4">
                <div class="title">
                    <span class="d-block">Total Product</span>
                    <span class="d-block">Ongkir</span>
                    <span class="d-block">Biaya Layanan</span>
                    <span class="d-block">Total Payment</span>
                </div>
                <div>
                    <span class="d-block">Rp.</span>
                    <span class="d-block">Rp.</span>
                    <span class="d-block">Rp.</span>
                    <span class="d-block">Rp.</span>
                </div>
                <div class="body">
                    <span class="d-block">{{ number_format($order->total, 0, ',', '.') }}</span>
                    <span class="d-block">10.000</span>
                    <span class="d-block">1.000</span>
                    <span class="d-block">{{ number_format(($order->total + 10000 + 1000), 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        <a href="{{ route('orders.list') }}" class="ml-2 mt-4 btn btn-primary">Back</a>
    </div>
</div>
@endsection
