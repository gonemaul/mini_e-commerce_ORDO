<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="{{ asset('invoice.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>

<style type="text/css">

:root {
    --primary: #B1ADD4;
    --font:#888;
}

*, html {
    margin: 0;
    padding: 0;
}

body {
    font-family: "Rubik", sans-serif;
}

.container {
    padding: 30px;
}

.title {
        font-size: 1.5rem;
        font-weight: 700;
        text-align: right;
        padding: 0 0 20px 0;
        color: var(--font);
}

.top .table-head {
    padding: 15px 0;
    border-top: 1.4px dashed var(--primary);
    border-bottom: 1.4px dashed var(--primary);
    width: 100%;
}

.top .table-head h1 {
    color: var(--font);
    font-size: .9rem;
    font-weight: 300
    font-family: "Helvetica Neue";
    text-align: center;
}

.top .table-content {
    padding: 15px 0 15px 0;
    font-size: .9rem;
}

.top .table-content .tc-head, .tc-content {
    color: var(--font);
}

.top .table-content .tc-content .label {
    padding: 0 30px 0 15px;
}

.bottom .table-head {
    font-family: "Rubik", sans-serif;

    border-top: 1.4px dashed var(--primary);
    border-bottom: 1.4px dashed var(--primary);
    width: 100%;
    text-align: center;
    padding: 10px 0;
    font-size: .9rem;

}
.bottom .table-head thead tr td, .bottom .table-head tbody tr td {
    font-size: .8rem;
    height: 30px;
    color: var(--font);
}

.bottom .table-head thead tr .label {
    color: var(--font);
    font-weight: 600;
    padding-bottom: 5px;
}

.bottom .table-head tbody tr .value {
    padding-top: 5px;
}

.bottom .table-head .border {
    background: var(--font);
    width: 1400%;
    height: 1px;
}

.bottom .table-content {
    padding: 10px 0;
    width: 100%;
    border-bottom: 1.4px dashed var(--primary);
    font-size: 0.9rem;
}

.bottom .table-content tr {
    padding: 0 0 13px 0;
}

.bottom .table-content tr td {
    color: var(--font);
}

.bottom .table-content thead .wp-label-value-top {
    padding: 0 0 7px 0;
    width: 500px;
}
.bottom .table-content thead .label{
    width: 130px;
}
.bottom .table-content thead .label{
    padding: 0 0 7px 0;
}
.footer{
    text-align: center;
    width: 100%;
    color: var(--font);
}
.footer .head{
    margin-top: 15px;
    display: block;
    font-size: 1.5rem;
    font-weight: 600;
}
.footer .mid{
    font-size: 0.9rem;
    display: block;
}
.footer .bot{
    display: block;
    font-size: 0.9rem;
}


</style>
<div class="container invoice">
    <h1 class="title">INVOICE</h1>
    <div class="top">
        <table class="table-head">
            <tr>
                <td><h1>Order ID : #{{ $order->order_id }}</h1></td>
                <td><h1>Date : {{ now() }}</h1></td>
            </tr>
        </table>
        <table class="table-content">
            <thead class="tc-head">
                <tr>
                    <td>To :</td>
                </tr>
            </thead>
            <tbody class="tc-content">
                <tr>
                    <td class="label">Name</td>
                    <td class="value">: {{ $order->name }}</td>
                </tr>
                <tr>
                    <td class="label">Email</td>
                    <td class="value">: {{ $order->email }}</td>
                </tr>
                <tr>
                    <td class="label">Phone</td>
                    <td class="value">: {{ $order->phone }}</td>
                </tr>
                <tr>
                    <td class="label">Address</td>
                    <td class="value">: {{ $order->address }}{{ $order->city }} {{ $order->postal_code }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="bottom">
        <table class="table-head">
            <thead>
                <tr class="tr-top">
                    <td class="label">No</td>
                    <td class="label">Item</td>
                    <td class="label">Category</td>
                    <td class="label">Price</td>
                    <td class="label">Qty</td>
                    <td class="label">Amount</td>
                </tr>
            </thead>
            <div class="border"></div>
            <tbody>
                @foreach ($order->orderItems as $index => $item)
                    <tr>
                        <td class="value">{{ $index + 1 }}</td>
                        <td class="value">{{ $item->product_name }}</td>
                        <td class="value">{{ $item->category_name }}</td>
                        <td class="value">Rp. {{ number_format($item->price) }}</td>
                        <td class="value">{{ $item->quantity }}</td>
                        <td class="value">Rp. {{ number_format($item->price * $item->quantity) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table class="table-content">
            <thead>
                <tr>
                    <td class="wp-label-value-top">
                        <p>Waktu pemesanan</p>
                    </td>
                    <td class="label">Total Product</td>
                    <td class="value">: {{ number_format($order->total, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="wp-label-value-top">
                        <p>{{ $order->created_at }}</p>
                    </td>
                    <td class="label">Discon</td>
                    <td class="value">: 0</td>
                </tr>
                <tr>
                    <td class="wp-label-value-bot">
                        <p>Waktu pemesanan</p>
                    </td>
                    <td class="label">Biaya Layanan</td>
                    <td class="value">: 1.000</td>
                </tr>
                <tr>
                    <td class="wp-label-value-bot">
                        <p>{{ $order->updated_at }}</p>
                    </td>
                    <td class="label">Total Payment</td>
                    <td class="value">: {{ number_format(($order->total  + 1000), 0, ',', '.') }}</td>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <div class="footer">
        <span class="head">
            THANK YOU!..
        </span>
        <span class="mid">
            Items that have been purchased cannot be returned
        </span>
        <span class="bot">
            For more info contact support@gonemaul.my.id
        </span>
    </div>
</div>
{!! $button !!}
