<?php

namespace App\Http\Controllers\Admin\Orders;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShowOrderController extends Controller
{
    public function __invoke($id){
        $order = Order::where('id' , $id)->with('items.product' , 'user.userInformation')->first();
        if(!$order){
            return response()->json([
                'status'    => 404,
                'message'   => 'No Order Found'
            ],404);
        }
        return response()->json([
            'status'    => 200,
            'data'      =>$order
        ]);
    }
}
