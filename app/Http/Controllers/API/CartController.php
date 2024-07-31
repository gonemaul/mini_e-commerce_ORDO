<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function add_to_cart(Request $request,Product $product){
        $request->validate([
            'quantity' => 'required',
        ]);

        $user = $request->user();
        $quantity = $request->quantity;

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
            'status' => 'success',
            'message' => 'Product add to cart successfully',
        ]);
    }

    public function update_cart(Request $request, Product $product){
        $request->validate([
            'quantity' =>'required',
        ]);
        $user = $request->user();
        $cartItem = CartItem::where('user_id',$user->id)
                                    ->where('product_id', $product->id)
                                    ->firstOrFail();

        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product quantity updated successfully',
        ]);
    }

    public function remove_from_cart(Product $product,Request $request){
        $user = $request->user();
        $cartItem = CartItem::where('user_id',$user->id)
                                    ->where('product_id',$product->id)
                                    ->firstOrFail()->delete();

        return response()->json([
           'status' =>'success',
           'message' => 'Product removed from cart successfully',
        ]);
    }
}
