<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('invoice.css') }}">
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>

{{-- <style>
body{
    padding: 0;
    margin: 0;
    overflow-x: hidden;
    font-size: 1rem;
    font-weight: normal;
    font-weight: initial;
    line-height: 1.5;
    font-family: "Rubik", sans-serif;
    -webkit-font-smoothing: antialiased;
}
.container{
    max-width: 75%;
    padding-right: 0.75rem;
    padding-left: 0.75rem;
    margin-right: auto;
    margin-left: auto;
}
.invoice{
    /* border: 2px solid #B1ADD4; */
    background-color: #ffff;
    border-radius: 5px;
    color: #6c7293;
    margin-top: 2rem;
    .header{
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        .name{
            padding-right: 1.5rem;
            font-size: 2rem;
            font-weight: 500;
            span{
                display: block;
                text-align: end;
            }
        }
    }
    .info{
        display: flex;
        justify-content: space-between;
        padding: 1rem;
    }
    .customer{
        margin-top: 0.5rem;
        padding-bottom: 1rem;
        .left{
            display: flex;
            align-content: center;
            justify-content: space-between;
            max-width: 25%;
            span{
                display: block;
            }
        }
    }
    .invoice_detail{
        border-bottom: 2px dashed #B1ADD4;
        .tabel_responsive{
            margin: 1rem 0;
            padding: 0 auto;
        }
        .table thead tr{
            padding: 0.9375rem;
            border-top: 0;
            border-bottom-width: 1px;
            font-weight: 500;
            color: #6c7293;
            text-align: center;
            border-bottom: 2px solid #6c7293;
            vertical-align: bottom;
        }
        .table tbody tr{
            color: #6c7293;
            text-align: center;
            border-top: 0;
            border-bottom-width: 1px;
            border-bottom: 1.5px solid #6c7293;
        }
    }
}
.bottom_info{
    border-bottom: 2px dashed #B1ADD4;
}
</style> --}}
<div class="container invoice">
    <div class="header">
        <div class="logo">
            {{-- <img src="{{ asset('logo.jpg') }}" alt="Logo" class="logo-img ml-4" style="width: 85px;height:85px"> --}}
        </div>
        <div class="name">
            <span>INVOICE</span>
        </div>
    </div>
    <div class="info" style="border-bottom: 2px dashed #B1ADD4;border-top: 2px dashed #B1ADD4">
        <span>Order ID : #{{ $order->order_id }}</span>
        <span>Invoice No : #{{ $order->invoice_id }}</span>
        <span>Date : {{ now() }}</span>
    </div>
    <div class="customer mt-2 pb-3" style="border-bottom: 2px dashed #B1ADD4;">
        <span style="font-size: 1.2rem">To :</span>
        <div class="left">
            <div class="title">
                <span>Customer Name</span>
                <span>Email</span>
                <span>Phone</span>
            </div>
            <div>
                <span>:</span>
                <span>:</span>
                <span>:</span>
            </div>
            <div>
                <span>{{ $order->name }}</span>
                <span>{{ $order->email }}</span>
                <span>{{ $order->phone }}</span>
            </div>
        </div>
    </div>
    <div class="invoice_detail">
        <div class="table-responsive tabel_responsive">
            <table class="table" style="width: 100%">
                <thead>
                    <tr>
                    <th style="font-weight:600;"> No </th>
                    <th style="font-weight:600;"> Item </th>
                    <th style="font-weight:600;"> Category </th>
                    <th style="font-weight:600;"> Price </th>
                    <th style="font-weight:600;"> Qty </th>
                    <th style="font-weight:600;"> Amount </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->category_name }}</td>
                            <td>Rp. {{ number_format($item->price) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp. {{ number_format($item->price * $item->quantity) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="bottom_info d-flex justify-content-between py-3 px-4">
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
                <span class="d-block">Discon</span>
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
    <div class="text-center py-3">
        THANK YOU!..
    </div>
</div>
{!! $button !!}
