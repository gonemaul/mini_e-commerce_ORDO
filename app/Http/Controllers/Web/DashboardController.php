<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(){
        return view('dashboard')->with([
            'title' => 'Dashboard',
            'users' => User::orderBy('created_at', 'desc')->get(),
            'products' => Product::orderBy('created_at','desc')->get(),
            'categories' => Category::orderBy('created_at','desc')->get(),
            'orders' => Order::orderBy('created_at','desc')->get()
        ]);
    }
}
