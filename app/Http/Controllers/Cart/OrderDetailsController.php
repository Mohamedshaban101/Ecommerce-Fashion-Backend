<?php

namespace App\Http\Controllers\Cart;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderDetailsController extends Controller
{
    public function __invoke($id){
        $order = Order::where([
            'id'    => $id,
            'user_id' => Auth::id()
        ])->with('items.product','user.userInformation')->first();

        if($order == null){
            return response()->json([
                'status'    => 404,
                'message' => 'Order Not Found'
            ],404);
        }
        return response()->json([
            'status'    => 200,
            'data'      => $order
        ],200);
    }
}
