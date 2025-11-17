<?php

namespace App\Http\Controllers\Cart;

use App\Models\Cart;
use App\Models\Shipping;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __invoke(){
        $cart = Cart::where(['user_id' => Auth::id() , 'is_paid' => false])->first();
        if($cart  == null){
            return response()->json([
                'status'   => 404,
                'data'     => [],
                'total'    => 0,
            ] , 200);
        }
        $subTotal = $cart->items->sum(function($item){
            return $item->product->price  * $item->quantity;
        });
        $shipping = Shipping::first();
        $amount = $shipping ? $shipping->shipping_charge : 0;
        
        $tax = round($subTotal * .04 , 2);
        $total = $subTotal + $tax + $amount;
        return response()->json([
            'status'        => 200,
            'data'          => [
                'cartDetail'    => $cart,
                'subtotal'      => $subTotal,
                'tax'           => $tax,
                'shipping'      => $amount,
                'total'         => $total,
                'totalItem'     => $cart->items->sum('quantity')
            ]
        ],200);
    }
}
