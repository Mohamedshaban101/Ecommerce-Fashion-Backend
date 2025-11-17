<?php

namespace App\Http\Controllers\Admin\Orders;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrdersController extends Controller
{
    public function __invoke(){
        $orders = Order::orderBy('created_at','DESC')->with('user')->get();
        return response()->json([
            'status'    => 200,
            'data'      => $orders
        ]);
    }
}
