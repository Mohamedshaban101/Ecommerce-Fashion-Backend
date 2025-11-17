<?php

namespace App\Http\Controllers\Cart;

use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RemoveItemFromCartController extends Controller
{
    public function __invoke($id){
        $item = CartItem::where('id' , $id)->first();
        $item->delete();
        return response()->json([
            'status'        => 200,
            'message'       => 'item removed from cart',
        ] , 200);
    }
}
