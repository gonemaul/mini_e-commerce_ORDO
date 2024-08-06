<?php

namespace App\Http\Controllers\API;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Order;
use App\Models\CartItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function checkout_orders(Request $request){

        $request->validate([
            'phone' => ['required','max:15'],
            'address' => ['required','max:100'],
        ]);

        $date = Carbon::now()->format('Ymd');
        $order_id = $date . rand(1000, 9999);
        $user = auth()->user();
        $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();

        if($cartItems->isEmpty()){
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        try {
            $total = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            $order = Order::create([
                'order_id' => $order_id,
                'user_id' => $user->id,
                'phone' => $request->phone,
                'address' => $request->address,
                'total' => $total,
            ]);

            $order_items = $cartItems->map(function ($item) use ($order) {
                if($item->product->stock < $item->quantity){
                    return response()->json([
                        'message' => 'Not enough stock for '. $item->product->name,
                        'stock' => $item->product->stock
                    ], 400);
                }
                $order_item = new OrderItem([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                $item->product->decrement('stock', $item->quantity);
                $item->product->increment('terjual', $item->quantity);

                return $order_item;
            });

            $order->orderItems()->saveMany($order_items);

            $params = [
                'transaction_details' => [
                    'order_id' => $order_id,
                    'gross_amount' => $total,
                ],
                'item_details' => $cartItems->map(function ($item) {
                        return [
                            'id' => $item->product_id,
                            'price' => intval($item->product->price),
                            'quantity' => $item->quantity,
                            'name' => $item->product->name,
                        ];
                }),
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $request->phone,
                    'billing_address' => [
                        'first_name' => $user->name,
                        'address' => $request->address,
                        'phone' => $request->phone,
                    ],
                    'shipping_address'=> [
                        'first_name' => $user->name,
                        'address' => $request->address,
                        'phone' => $request->phone,
                    ]
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            $order->snap_token = $snapToken;
            $order->save();

            $cartItems = CartItem::where('user_id', $user->id)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Please ndang bayar!!',
                'payment' => [
                    'token' => $snapToken,
                    'link' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/'.$snapToken,
                ]
            ],201);

        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'meta' => null,
                'status' => 'error',
                'message' => 'An error occurred',
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }

    public function order_history(){
        try {
            $history = Order::where('user_id', auth()->user()->id)->with('orderItems.product')->get();
            $order_history = $history->map(function ($item) {
                $order_items = $item->orderItems->map(function ($orderItem) {
                    return [
                        'product_name' => $orderItem->product->name,
                        'category' => $orderItem->product->category->name,
                        'quantity' => $orderItem->quantity,
                        'price' => $orderItem->price,
                    ];
                });

                return [
                    'order_id' => $item->order_id,
                    'status' => $item->status,
                    'total' => $item->total,
                    'order_items' => $order_items,
                ];
            });

            $order_history = $order_history->toArray();

            return response()->json([
                'status' => 'success',
                'message' => 'Data retrieved successfully',
                'order_history' => $order_history
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
               'meta' => null,
               'status' => 'error',
               'message' => 'An error occurred',
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }
}
