<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function add_to_cart(Request $request,Product $product){
        try{
            $request->validate([
                'quantity' => 'required',
            ]);

            $user = $request->user();
            $quantity = $request->quantity;

            if($product->stock < $quantity){
                return response()->json([
                    'status' => __('general.error'),
                    'message' => __('order.cart.error'). $product->name,
                    'stock' => $product->stock
                ],400);
            }

            $cartItem = CartItem::where('user_id',$user->id)
                                        ->where('product_id',$product->id)
                                        ->first();

            if($cartItem){
                $cartItem->quantity += $quantity;
                $cartItem->save();
            }
            else{
                $cartItem = CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity
                ]);
            }

            return response()->json([
                'status' => __('general.success'),
                'message' => __('order.cart.success'),
                'data' => [
                    'user' => $user->name,
                    __('product.name') => $cartItem->product->name,
                    __('general.quantity') => $cartItem->quantity,
                    __('general.price') => $cartItem->product->price,
                    __('general.total') => $cartItem->product->price * $cartItem->quantity
                ]

            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
               'meta' => null,
               'status' => __('general.error'),
               'message' => __('general.message.error'),
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }

    public function show(Request $request){
        try{
            $user = $request->user();
            $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();
            $totalPrice = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            if($cartItems->isEmpty()){
                return response()->json([
                    'status' => __('general.error'),
                    'message' => __('order.cart.kosong'),
                ],200);
            }
            return response()->json([
                'data' => [
                    'cartItems' => $cartItems->map(function ($cartItem) {
                        return [
                            __('product.name') => $cartItem->product->name,
                            __('general.category') => $cartItem->product->category->name,
                            __('general.quantity') => $cartItem->quantity,
                            __('general.price') => $cartItem->product->price,
                        ];
                    }),
                    'totalPrice' => $totalPrice
                ],
                'status' => __('general.success'),
                'message' => __('general.message.success'),

            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
               'meta' => null,
               'status' => __('general.error'),
               'message' => __('general.message.error'),
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }

    public function update_cart(Request $request, Product $product){
        try {
            $request->validate([
                'quantity' =>'required',
            ]);
            $user = $request->user();
            $cartItem = CartItem::where('user_id',$user->id)
                                        ->where('product_id', $product->id)
                                        ->firstOrFail();

            if($product->stock < $request->quantity){
                return response()->json([
                    'status' => __('general.error'),
                    'message' => __('order.cart.error'). $product->name,
                    'stock' => $product->stock
                ],400);
            }

            $cartItem->update([
                'quantity' => $request->quantity
            ]);

            return response()->json([
                'status' => __('general.success'),
                'message' => __('order.cart.update'),
                'data' => [
                    __('product.name') => $cartItem->product->name,
                    __('general.quantity') => $cartItem->quantity,
                    __('general.price') => $cartItem->product->price,
                    __('general.total') => $cartItem->product->price * $cartItem->quantity
                ],
            ],200);
        } catch (\Exception $e){
            return response()->json([
                'data' => null,
               'meta' => null,
               'status' => __('general.error'),
               'message' => __('general.message.error'),
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }

    public function remove_from_cart(Product $product,Request $request){
        try{
            $user = $request->user();
            CartItem::where('user_id',$user->id)
                            ->where('product_id',$product->id)
                            ->firstOrFail()->delete();

            return response()->json([
               'status' =>__('general.success'),
               'message' => __('order.cart.remove.')
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
               'meta' => null,
               'status' => __('general.error'),
               'message' => __('general.message.error'),
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }
}
