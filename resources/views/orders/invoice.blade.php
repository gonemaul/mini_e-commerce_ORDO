<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('invoice.css') }}">
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>

<style type="text/css">
*{
    box-sizing: border-box;
    font-size: 20px;
}
body{
    padding: 0;
    margin: 0;
    overflow-x: hidden;
    font-weight: normal;
    font-weight: initial;
    line-height: 1.5;
    font-family: "Rubik", sans-serif;
    -webkit-font-smoothing: antialiased;
    color: #6c7293;
    /* background-color: #191c24; */
}
.container{
    max-width: 80%;
    padding-right: 1rem;
    padding-left: 1rem;
    margin-right: auto;
    margin-left: auto;
}
.invoice{
    background-color: #ffff;
    border-radius: 5px;
    color: #6c7293;
    margin-top: 2rem;
    .header{
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        .name{
            margin: auto 0;
            padding-right: 1.5rem;
            font-size: 2rem;
            font-weight: 550;
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
        /* width: 100%; */
        span{
            margin: 0 auto;
        }
    }
    .customer{
        margin-top: 0.5rem;
        padding-bottom: 1rem;
        .left{
            display: flex;
            align-content: center;
            justify-content: space-between;
            max-width: 30%;
            margin-left: 1rem;
            span{
                display: block;
            }
            .body{
                max-width: 75%;
            }
        }
    }
    .invoice_detail{
        border-bottom: 2px dashed #B1ADD4;
        .tabel_responsive{
            margin: 1rem 0;
            padding: 0 auto;
        }
        .table{
            border-collapse: collapse;
            thead th{
                padding: 0.9375rem;
                border-top: 0;
                /* border-bottom-width: 1px; */
                font-weight: 500;
                color: #6c7293;
                text-align: center;
                border-bottom: 2px solid #6c7293;
                vertical-align: bottom;
            }
            td{
                vertical-align: middle;
                font-size: 0.875rem;
                line-height: 1;
                white-space: nowrap;
                padding: 0.9375rem;
            }
            tbody tr{
                color: #6c7293;
                text-align: center;
                border-top: 0;
                border-bottom-width: 1px;
                border-bottom: 1.5px solid #6c7293;
            }
        }
    }
}
.bottom_info{
    border-bottom: 2px dashed #B1ADD4;
    display: flex;
    justify-content: space-between;
    padding: 1rem 1.5rem;
    .left{
        .mb-2{
            margin-bottom: 0.5rem;
        }
        span{
            display: block;
        }
    }
    .right{
        display: flex;
        justify-content: space-between;
        width: 20%;
        span{
            display: block;
        }
    }
}
.text-center{
    text-align: center;
    padding: 1rem 0;
    span.title{
        font-size: 1.5rem;
        font-weight: 500;
    }
    span{
        display: block;
    }
}
</style>
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
        <span>Date : {{ now() }}</span>
    </div>
    <div class="customer mt-2 pb-3" style="border-bottom: 2px dashed #B1ADD4;">
        <span style="font-size: 1.2rem">To :</span>
        <div class="left">
            <div class="title">
                <span>Name</span>
                <span>Email</span>
                <span>Phone</span>
                <span>Address</span>
            </div>
            <div>
                <span>:</span>
                <span>:</span>
                <span>:</span>
                <span>:</span>
            </div>
            <div class="body">
                <span>{{ $order->name }}</span>
                <span>{{ $order->email }}</span>
                <span>{{ $order->phone }}</span>
                <span>
                    {{ $order->address }}
                    {{ $order->city }}
                    {{ $order->postal_code }}
                </span>
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
    <div class="bottom_info">
        <div class="left">
            <div class="mb-2">
                <span class="d-block">Waktu pemesanan</span>
                <span>{{ $order->created_at }}</span>
            </div>
            <div>
                <span class="d-block">Waktu pembayaran</span>
                <span>{{ $order->updated_at }}</span>
            </div>
        </div>
        <div class="right">
            <div class="title">
                <span>Total Product</span>
                <span>Discon</span>
                <span>Biaya Layanan</span>
                <span>Total Payment</span>
            </div>
            <div class="middle">
                <span>Rp.</span>
                <span>Rp.</span>
                <span>Rp.</span>
                <span>Rp.</span>
            </div>
            <div class="body">
                <span>{{ number_format($order->total, 0, ',', '.') }}</span>
                <span>0</span>
                <span>1.000</span>
                <span>{{ number_format(($order->total  + 1000), 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
    <div class="text-center">
        <span class="title">
            THANK YOU!..
        </span>
        <span>
            Items that have been purchased cannot be returned
        </span>
        <span>
            For more info contact support@gonemaul.my.id
        </span>
    </div>
</div>
{!! $button !!}
