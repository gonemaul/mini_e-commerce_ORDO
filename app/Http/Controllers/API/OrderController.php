<?php

namespace App\Http\Controllers\API;

use Midtrans\Snap;
use App\Models\User;
use Midtrans\Config;
use App\Models\Order;
use App\Models\CartItem;
use App\Models\OrderItem;
use Midtrans\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Notifications\NewOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ChangeStatusOrder;
use Illuminate\Support\Facades\Notification;

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
            'city' => ['required','max:50'],
            'postal_code' => ['required','max:50'],
        ]);

        $date = Carbon::now()->format('Ymd');
        $order_id = $date . rand(10000, 99999);
        $user = $request->user();
        $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();
        $lastOrder = Order::orderBy('created_at', 'desc')->first();

        if($cartItems->isEmpty()){
            return response()->json(['message' => __('order.cart.kosong')], 400);
        }
        foreach($cartItems as $item){
            if($item->product->stock < $item->quantity){
                return response()->json([
                    'message' => __('order.cart.error'). $item->product->name,
                    'stock' => $item->product->stock
                ], 400);
            }
        }
        try {
            $total = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            $order = Order::create([
                'order_id' => $order_id,
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'total' => $total,
            ]);

            $order_items = $cartItems->map(function ($item) use ($order) {
                $order_item = new OrderItem([
                    'order_id' => $order->id,
                    'product_id' => $item->product->id,
                    'product_name' => $item->product->name,
                    'category_name' => $item->product->category->name,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
                $item->product->decrement('stock', $item->quantity);
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
                            'name' => $item->product->name,
                            'price' => intval($item->product->price),
                            'quantity' => $item->quantity,
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
            $invoice = Pdf::loadView('orders.invoice', ['order' => $order, 'button' => '']);
            $path = storage_path('app/public/invoices/' . 'invoice_'.$order->order_id.'.pdf');
            Storage::put('invoices/' . 'invoice_'.$order->order_id.'.pdf', $invoice->output());
            $users = User::where('is_admin', true)->orWhere('id', $user->id)->get();
            if($users){
                Notification::send($users, new NewOrder($order,$path));
            }
            if($lastOrder){
                Storage::delete('invoices/' . 'invoice_'.$lastOrder->order_id . '.pdf');
            }
            return response()->json([
                'status' => __('general.success'),
                'message' => __('order.notif_payment'),
                'payment' => [
                    'token' => $snapToken,
                    'link' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/'.$snapToken,
                ]
            ],201);

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

    public function order_history(Request $request){
        try {
            $history = Order::where('user_id', $request->user()->id)->with('orderItems.product')->paginate(10);
            $order_history = $history->map(function ($item) {
                $order_items = $item->orderItems->map(function ($orderItem) {
                    return [
                        __('product.name') => $orderItem->product_name,
                        __('general.category') => $orderItem->category_name,
                        __('general.quantity') => $orderItem->quantity,
                        __('general.price') => $orderItem->price,
                    ];
                });

                return [
                    'order_id' => $item->order_id,
                    'status' => $item->status,
                    'total' => $item->total,
                    'order_items' => $order_items,
                    'payment_url' => $item->status === 'Pending' ? 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $item->snap_token : ''
                ];
            });

            $order_history = $order_history->toArray();

            return response()->json([
                'data' => $order_history,
                'meta' => [
                    'pagination' => [
                        'total' => $history->total(),
                        'per_page' => $history->perPage(),
                        'current_page' => $history->currentPage(),
                        'last_page' => $history->lastPage(),
                    ]
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

    public function cancel_order($order_id){
        $order = Order::where('order_id', $order_id)->first();
        $users = User::where('is_admin', true)->orWhere('id', $order->user_id)->get();
        try {
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

            $canceled = Transaction::cancel($order_id);

            if ($canceled == 200) {
                $order->status = 'Canceled';
                $order->save();
                Notification::send($users, new ChangeStatusOrder($order));
                return response()->json(['message' => __('order.cancel_order.success')], 200);
            } else {
                return response()->json(['message' => __('order.cancel_order.failed')], 500);
            }
        } catch (\Exception $e) {
            $order->status = 'Canceled';
            $order->save();
            Notification::send($users, new ChangeStatusOrder($order));
            return response()->json(['message' => __('order.cancel_order.success')], 200);
        }
    }

    public function invoice($order_id){
        $order = Order::with(['user','orderItems.product.category', 'orderItems.product.productImage'])->where('order_id', $order_id)->first();

        // Buat PDF dari tampilan
        $pdf = Pdf::loadView('orders.invoice', ['order' => $order, 'button' => '']);

        // Mengunduh PDF dengan nama file tertentu
        return $pdf->download('invoice_'.$order->order_id.'.pdf');
    }
}