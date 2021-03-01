<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function orders()
    {
        $orders = Order::get();

        $orders->transform(function($order, $key) {

            $order->cart = unserialize($order->cart);

            return $order;
        });

        return view('admin.orders',compact('orders'));
    }
}
