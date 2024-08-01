@extends('layout.main')

@section('content')
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>
<div class="content-wrapper">
    <div class="p-4" style="background-color: #191c24;border-radius:0.5rem">
        <div class="status bg-warning p-3 mb-3 text-center" style="border-radius: 0.5rem;font-size:1rem;font-weight:600">
            <span>Status : </span>
            <span>Pending</span>
        </div>
        <div class="info p-3">
            <div class="buyyer mb-3">
                <div class="title mb-1">
                    <i class="fa-solid fa-user mr-1"></i> {{ $order->user->name }}
                </div>
                <div class="body ml-4">
                    <span>{{ $order->user->email }}</span>
                </div>
            </div>
        </div>
        <div class="product-item ">
            @foreach ($order->orderItems as $index => $item)
                <div class="row mb-3 p-3 m-0" style="border-radius:0.5rem;border: 1px solid #8c8c8c;color: ">
                    <div class="col-md-12 d-flex justify-content-between" style="width: 100%">
                        <div class="detail-left d-flex">
                            <div class="product-image d-flex justify-content-between">
                                @if($item->product->productImage->isNotEmpty())
                                    <img class="rounded" style="width: 80px; height: 80px;border: 1px solid #8c8c8c;" src="{{ asset('storage/' . $item->product->productImage->first()->image) }}">
                                @else
                                    <img class="rounded" style="width: 80px; height: 80px:border: 1px solid #8c8c8c;" src="{{ asset('assets/images/no-image.png') }}">
                                @endif
                            </div>
                            <div class="name ml-4">
                                <h4>{{ $item->product->name }}</h4>
                                <label for="" class="badge badge-outline-primary">{{ $item->product->category->name }}</label>
                            </div>
                        </div>
                        <div class="info-right pt-3">
                            <span class="text-muted">{{ $item->quantity }} x</span>
                            <div class="text-end">
                                Rp. {{ number_format($item->price * $item->quantity) }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="details-payment p-3 col-md-4 ml-auto">
            <div class="total-products d-flex justify-content-between">
                <span>Total Product</span>
                <span>Rp. {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
            <div class="delivery d-flex justify-content-between">
                <span>Ongkir</span>
                <span>Rp. 10.000</span>
            </div>
            <div class="pajak d-flex justify-content-between">
                <span>Biaya Layanan</span>
                <span>Rp. 1.000</span>
            </div>
            <div class="total-payment d-flex justify-content-between">
                <span>Total Payment</span>
                <span>Rp. {{ number_format(($order->total + 10000 + 1000), 0, ',', '.') }}</span>
            </div>
        </div>
        <a href="{{ route('orders.list') }}" class="ml-2 mt-4 btn btn-secondary">Back</a>
        <a href="{{ route('orders.list') }}" class="ml-2 mt-4 btn btn-primary">Payment</a>
    </div>
</div>
@endsection
