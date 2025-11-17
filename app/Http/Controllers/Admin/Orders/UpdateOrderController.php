<?php

namespace App\Http\Controllers\Admin\Orders;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UpdateOrderController extends Controller
{
    public function __invoke(Request $request , $id){
        $order = Order::where('id' , $id)->first();
        
        if($order === null){
            return response()->json([
                'status'    => 404,
                'message'      => 'No Order Found'
            ],404);
        }
        if($request->status){
            $order->update([
                'status'        => $request->status,
            ]);
        }
        if($request->payment_status){
            $order->update([
                'payment_status'  => $request->payment_status,
            ]);
        }
        return response()->json([
            'status'    => 200,
            'message'   => 'Order Updated Successfully'
        ]);
    }
}
