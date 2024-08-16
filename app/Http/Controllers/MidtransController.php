<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Notifications\ChangeStatusOrder;
use Illuminate\Support\Facades\Notification;

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
        $users = User::where('is_admin', true)->orWhere('id', $order->user_id)->get();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        switch ($transactionStatus) {
            case 'capture':
                if ($request->payment_type == 'credit_card') {
                    if ($request->fraud_status == 'challenge') {
                        $order->update(['status' => 'Pending']);
                        Notification::send($users, new ChangeStatusOrder($order));
                    } else {
                        $order->update(['status' => 'Success']);
                        Notification::send($users, new ChangeStatusOrder($order));
                        $order->orderItems->map(function ($item) {
                            $item->product->increment('sold', $item->quantity);
                        });
                    }
                }
                break;
            case 'settlement':
                $order->update(['status' => 'Success']);
                Notification::send($users, new ChangeStatusOrder($order));
                $order->orderItems->map(function ($item) {
                    $item->product->increment('sold', $item->quantity);
                });
                break;
            case 'pending':
                $order->update(['status' => 'Pending']);
                Notification::send($users, new ChangeStatusOrder($order));
                break;
            case 'deny':
                $order->update(['status' => 'Failed']);
                Notification::send($users, new ChangeStatusOrder($order));
                $order->orderItems->map(function ($item) {
                    $item->product->increment('stock', $item->quantity);
                });
                break;
            case 'expire':
                $order->update(['status' => 'Expired']);
                Notification::send($users, new ChangeStatusOrder($order));
                $order->orderItems->map(function ($item) {
                    $item->product->increment('stock', $item->quantity);
                });
                break;
            case 'cancel':
                $order->update(['status' => 'Canceled']);
                Notification::send($users, new ChangeStatusOrder($order));
                $order->orderItems->map(function ($item) {
                    $item->product->increment('stock', $item->quantity);
                });
                break;
            default:
                $order->update(['status' => 'Unknown']);
                Notification::send($users, new ChangeStatusOrder($order));
                break;
        }

        return response()->json(['message' => 'Callback received successfully']);
    }
}