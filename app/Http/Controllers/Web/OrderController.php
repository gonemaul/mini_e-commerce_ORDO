<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Midtrans\Config;
use App\Models\Order;
use Midtrans\Transaction;
use App\Exports\OrderExport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications\ChangeStatusOrder;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Notification;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class OrderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:order_view|order_view_detail|order_update', only: ['load_data']),
            new Middleware('permission:order_view_detail', only: ['detail']),
            new Middleware('permission:order_export', only: ['export']),
            new Middleware('permission:order_update', only: ['cancel_order']),
            new Middleware('permission:order_update|order_export|order_view_detail|order_view', only: ['index']),
        ];
    }
    public function index(){
        return view('orders.index')->with([
            'title' => 'Orders',
        ]);
    }

    public function load_data(){
        $orders = Order::select(['id','order_id','name','total','status']);
        return DataTables::of($orders)
        ->addIndexColumn()
        ->addColumn('total',function($orders){
            return 'Rp. '.number_format($orders->total, 0, ',', '.');
        })
        ->addColumn('status', function($orders){
            switch($orders->status){
                case 'Success':
                    return '<label class="badge badge-outline-success"><i class="fa-regular fa-circle-check mr-2"></i>'. __('order.status.success') .'</label>';

                case 'Pending':
                    return '<label class="badge badge-outline-warning"><i class="fa-regular fa-clock mr-2"></i> '. __('order.status.pending') .'</label>';

                case'Failed';
                    return '<label class="badge badge-outline-danger">'. __('order.status.failed') .'</label>';

                case'Expired';
                    return '<label class="badge badge-outline-info">'. __('order.status.expired') .'</label>';

                case'Canceled';
                    return '<label class="badge badge-outline-danger"><i class="fa-solid fa-xmark mr-2"></i>'. __('order.status.canceled') .'</label>';

                default:
                    return '<label class="badge badge-outline-secondary">'. $orders->status .'</label>';
            }
        })
        ->addColumn('action', function($orders){
            if($orders->status == "Pending"){
                $btn = '<button type="submit" class="btn btn-outline-danger" onclick="return confirm(\'What are you sure? ..\');" style="font-size:1rem"><i class="fa-solid fa-xmark"></i>'. __('general.cancel').'</button>';
            }
            else {
                $btn = '<button type="submit" disabled class="btn btn-outline-danger" onclick="return confirm("What are you sure? ..")" style="font-size:1rem"><i class="fa-solid fa-xmark"></i>'. __('general.cancel').'</button>';
            }
            $detail = '';
            $cancel = '';
            if(Gate::allows('order_view_detail')){
                $detail = '<a href="'. route('orders.detail', $orders->id) .'" class="btn btn-outline-primary mr-1"><i class="fa-solid fa-eye"></i> Detail</a>';
            }
            if(Gate::allows('order_update')){
                $cancel = '<form action="'. route('orders.cancel', $orders->order_id) .'" method="post">
                        '.csrf_field().'
                        '.$btn.
                        '</form>';
            }
            return $detail.$cancel;
        })
        ->rawColumns(['status','action'])
        ->make(true);
    }

    public function detail($id){
        return view('orders.detail')->with([
            'title' => 'Order Detail',
            'order' => Order::with(['user','orderItems.product.category', 'orderItems.product.productImage'])->findOrFail($id)
        ]);
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
                return redirect()->back()->with(['success' => __('order.cancel_order.success')]);
            } else {
                return redirect()->back()->with(['error' => __('order.cancel_order.failed')]);
            }
        } catch (\Exception $e) {
            $order->status = 'Canceled';
            $order->save();
            Notification::send($users, new ChangeStatusOrder($order));
            return redirect()->back()->with(['success' => __('order.cancel_order.success')]);
        }
    }

    public function export(){
        $name = 'Orders_' . Carbon::now()->format('Ymd') . rand(10,99) . '.xlsx';
        return Excel::download(new OrderExport(), $name);
    }

    public function invoice($order_id){
        $order =Order::with(['user','orderItems.product.category', 'orderItems.product.productImage'])->where('order_id', $order_id)->first();
        $order->invoice_id = now()->format('Ymd') . rand(100,999);
        return view('orders.invoice')->with([
            'title' => 'Invoice',
            'order' => $order,
            'button' => '<div class="container mt-3">
                <a href="'. route('orders.invoice_download', $order->order_id) .'" class="btn btn-outline-info" style="font-size:1rem"><i class="fa-regular fa-file-pdf"></i>'.__('order.cancel_order.download').'</a>
                <a href="'. route('orders.invoice_preview', $order->order_id) .'" target="blank" class="btn btn-outline-warning" style="font-size:1rem"><i class="fa-solid fa-print"></i>'.__('order.cancel_order.preview').'</a>
            </div>'
        ]);
    }

    public function invoice_download($order_id){
        $order = Order::with(['user','orderItems.product.category', 'orderItems.product.productImage'])->where('order_id', $order_id)->first();

        $pdf = Pdf::loadView('orders.invoice', ['order' => $order, 'button' => '']);

        return $pdf->download('invoice_'.$order->order_id.'.pdf');
    }

    public function invoice_preview($order_id){
        $order = Order::with(['user','orderItems.product.category', 'orderItems.product.productImage'])->where('order_id', $order_id)->first();

        $pdf = Pdf::loadView('orders.invoice', ['order' => $order, 'button' => '']);

        return $pdf->stream('invoice_'.$order->order_id.'.pdf');
    }
}