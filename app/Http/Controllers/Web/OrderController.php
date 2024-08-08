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
            'orders' => Order::with(['orderItems'])->paginate(10)
        ]);
    }

    public function detail($id){
        return view('orders.detail')->with([
            'title' => 'Order Detail',
            'order' => Order::with(['user','orderItems.product.category', 'orderItems.product.productImage'])->findOrFail($id)
        ]);
    }

    public function cancel_order($order_id){
        $order = Order::where('order_id', $order_id)->first();

        try {
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

            $canceled = Transaction::cancel($order_id);

            if ($canceled == 200) {
                $order->status = 'Canceled';
                $order->save();

                // return response()->json(['message' => 'Order canceled successfully'], 200);
                return redirect()->route('orders.list');
            } else {
                // return response()->json(['message' => 'Failed to cancel order'], 500);
                return redirect()->route('orders.list');
            }
        } catch (\Exception $e) {
            return redirect()->route('orders.list');
            // return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

}
