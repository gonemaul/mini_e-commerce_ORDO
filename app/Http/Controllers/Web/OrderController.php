<?php

namespace App\Http\Controllers\Web;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index(){
        return view('orders.index')->with([
            'title' => 'Orders',
        ]);
    }

    public function load_data(){
        return view('orders.item_tabel')->with([
            'orders' => Order::with(['user', 'orderItems'])->paginate(10)
        ]);
    }

    public function detail($id){
        return view('orders.detail')->with([
            'title' => 'Order Detail',
            'order' => Order::with(['user','orderItems.product.category', 'orderItems.product.productImage'])->findOrFail($id)
        ]);
    }
}
