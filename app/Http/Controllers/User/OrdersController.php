<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    public function __invoke(){
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized'
            ], 401);
        }
        $orders = Order::where('user_id' , Auth::id())->with('items')->get();
        if($orders->isEmpty()){
            return response()->json([
                'status' => 404,
                'message'=> 'No Orders Found'
            ]);
        }
        return response()->json([
            'status'    => 200,
            'data'      => $orders
        ]);
    }
}
