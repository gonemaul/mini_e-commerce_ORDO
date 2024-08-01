<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use App\Models\CartItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function checkout_orders(){
        try {
            $user = auth()->user();

            $cartItems = CartItem::where('user_id', $user->id)->with('product');
            if($cartItems->get()->isEmpty()){
                return response()->json(['message' => 'Cart is empty'], 400);
            }
            else{
                $total = 0;
                foreach ($cartItems->get() as $cartItem) {
                    if($cartItem->product->stock < $cartItem->quantity){
                        return response()->json([
                            'message' => 'Not enough stock for '. $cartItem->product->name,
                            'stock' => $cartItem->product->stock
                        ], 400);
                    }
                    $cartItem->product->decrement('stock', $cartItem->quantity);
                    $cartItem->product->increment('terjual', $cartItem->quantity);
                    $total += $cartItem->product->price * $cartItem->quantity;
                }

                $order = Order::create([
                    'user_id' => $user->id,
                    'total' => $total,
                   'status' => 'pending',
                ]);

                foreach ($cartItems->get() as $cartItem) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cartItem->product_id,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->product->price,
                    ]);
                }

                $cartItems->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Checkout successfully',
                ]);
            }
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
                    'id' => $item->id,
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
