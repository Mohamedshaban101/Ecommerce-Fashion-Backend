<?php

namespace App\Http\Controllers\Cart;

use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IncrementQuantityController extends Controller
{
    public function __invoke(Request $request){
        $item = CartItem::where('id' , $request->id)->first();
        $newQuantity = (int) $request->quantity;
        $newPrice = (int) $item->product->price * $newQuantity;
        $item->update([
            'quantity' => $newQuantity,
            'price'    => $newPrice
        ]);
        return response()->json([
            'status'        => 200,
            'data'          => $item
        ]);
    }
}
