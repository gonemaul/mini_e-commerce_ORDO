<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class MidtransController extends Controller
{
    public function callback(Request $request){
        $serverKey = config('midtrans.server_key');
        $hashedKey = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashedKey !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        $transactionStatus = $request->transaction_status;
        $orderId = $request->order_id;
        $order = Order::where('order_id', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        switch ($transactionStatus) {
            case 'capture':
                if ($request->payment_type == 'credit_card') {
                    if ($request->fraud_status == 'challenge') {
                        $order->update(['status' => 'Pending']);
                    } else {
                        $order->update(['status' => 'Success']);
                        $order->orderItems->map(function ($item) {
                            $item->product->decrement('stock', $item->quantity);
                            $item->product->increment('sold', $item->quantity);
                        });
                    }
                }
                break;
            case 'settlement':
                $order->update(['status' => 'Success']);
                $order->orderItems->map(function ($item) {
                    $item->product->decrement('stock', $item->quantity);
                    $item->product->increment('sold', $item->quantity);
                });
                break;
            case 'pending':
                $order->update(['status' => 'Pending']);
                break;
            case 'deny':
                $order->update(['status' => 'Failed']);
                break;
            case 'expire':
                $order->update(['status' => 'Expired']);
                break;
            case 'cancel':
                $order->update(['status' => 'Canceled']);
                break;
            default:
                $order->update(['status' => 'Unknown']);
                break;
        }

        return response()->json(['message' => 'Callback received successfully']);
    }
}
