<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Midtrans\Config;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Midtrans\Transaction;
use App\Exports\OrderExport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications\ChangeStatusOrder;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
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
                    return '<label class="badge badge-outline-success"><i class="fa-regular fa-circle-check mr-2"></i>'. $orders->status .'</label>';

                case 'Pending':
                    return '<label class="badge badge-outline-warning"><i class="fa-regular fa-clock mr-2"></i> '. $orders->status .'</label>';

                case'Failed';
                    return '<label class="badge badge-outline-danger">'. $orders->status .'</label>';

                case'Expired';
                    return '<label class="badge badge-outline-info">'. $orders->status .'</label>';

                case'Canceled';
                    return '<label class="badge badge-outline-danger"><i class="fa-solid fa-xmark mr-2"></i>'. $orders->status .'</label>';

                default:
                    return '<label class="badge badge-outline-secondary">'. $orders->status .'</label>';
            }
        })
        ->addColumn('action', function($orders){
            if($orders->status == "Pending")
                $btn = '<button type="submit" class="btn btn-outline-danger" onclick="return confirm(\'What are you sure? ..\');" style="font-size:1rem"><i class="fa-solid fa-xmark"></i> Cancel</button>';
            else
                $btn = '<button type="submit" disabled class="btn btn-outline-danger" onclick="return confirm("What are you sure? ..")" style="font-size:1rem"><i class="fa-solid fa-xmark"></i> Cancel</button>';

            return '<a href="'. route('orders.detail', $orders->id) .'" class="btn btn-outline-primary mr-1"><i class="fa-solid fa-eye"></i> Detail</a>
                    <form action="'. route('orders.cancel', $orders->order_id) .'" method="post">
                        '.csrf_field().'
                        '.$btn.
                        '</form>';
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
                return redirect()->back()->with(['success' => 'Order canceled successfully']);
            } else {
                return redirect()->back()->with(['error' => 'Failed to cancel order']);
            }
        } catch (\Exception $e) {
            $order->status = 'Canceled';
            $order->save();
            Notification::send($users, new ChangeStatusOrder($order));
            return redirect()->back()->with(['success' => 'Order canceled successfully']);
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
            <a href="'. route('orders.invoice_download', $order->order_id) .'" class="btn btn-outline-info" style="font-size:1rem"><i class="fa-regular fa-file-pdf"></i>Download</a>
            <a href="'. route('orders.invoice_preview', $order->order_id) .'" target="blank" class="btn btn-outline-warning" style="font-size:1rem"><i class="fa-solid fa-print"></i>Preview</a>
            </div>'
        ]);
    }

    public function invoice_download($order_id){
        $order = Order::with(['user','orderItems.product.category', 'orderItems.product.productImage'])->where('order_id', $order_id)->first();

        // Buat PDF dari tampilan
        $pdf = Pdf::loadView('orders.invoice', ['order' => $order, 'button' => '']);

        // Mengunduh PDF dengan nama file tertentu
        return $pdf->download('invoice_'.$order->order_id.'.pdf');
    }

    public function invoice_preview($order_id){
        $order = Order::with(['user','orderItems.product.category', 'orderItems.product.productImage'])->where('order_id', $order_id)->first();

        // Buat PDF dari tampilan
        $pdf = Pdf::loadView('orders.invoice', ['order' => $order, 'button' => '']);

        // Mengunduh PDF dengan nama file tertentu
        return $pdf->stream('invoice_'.$order->order_id.'.pdf');
    }
}