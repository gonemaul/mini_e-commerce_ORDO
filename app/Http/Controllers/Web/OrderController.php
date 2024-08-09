<?php

namespace App\Http\Controllers\Web;

use Midtrans\Config;
use App\Models\Order;
use Midtrans\Transaction;
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
            'orders' => Order::with(['orderItems'])->orderBy('created_at', 'desc')->get()
        ]);
    }

    public function detail($id){
        return view('orders.detail')->with([
            'title' => 'Order Detail',
            'order' => Order::with(['user','orderItems.product.category', 'orderItems.product.productImage'])->findOrFail($id)
        ]);
    }

    public function cancel_order($order_id){

        try {
            $order = Order::where('order_id', $order_id)->first();
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

            $canceled = Transaction::cancel($order_id);

            if ($canceled == 200) {
                $order->status = 'Canceled';
                $order->save();

                return redirect()->back()->with(['success' => 'Order canceled successfully']);
            } else {
                return redirect()->back()->with(['error' => 'Failed to cancel order']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Order transaction not found in midtrans']);
        }
    }

}
