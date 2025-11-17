<?php

namespace App\Http\Controllers\Cart;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AddToCartController extends Controller
{
    public function __invoke(Request $request){
        $validator = Validator::make($request->all() , [
            'quantity'      => ['required' , 'integer'],
            'size'          => ['required'],
            'product_id'    => ['required' , 'exists:products,id'],
        ]);
        if($validator->fails()){
            return response()->json([
                'status'        => 422,
                'error'         => $validator->errors()
            ],422);
        }
        $size ='';
        switch($request->size){
            case 1 :
                $size = 'S';
                break;
            case 2 : 
                $size = 'M';
                break;
            case 3 : 
                $size = 'XL';
                break;
            case 4 :
                $size = 'XXL';
                break;
            case 5 :
                $size = 'L';
                break;
            default :
                $size = '';
        }
        $cart = Cart::firstOrCreate(['user_id' => Auth::id() , 'is_paid' => false]);

        $item = CartItem::where('cart_id' , $cart->id)->where('product_id' , $request->product_id)->where('size' , $size)->first();
        $product = Product::where('id' , $request->product_id)->first();

        if(!$product){
            return response()->json([
                'status'    => 404,
                'message'   => 'Product Not Found To Add To Cart'
            ],404);
        }
        if($item){
            $item->increment('quantity');
            $item->update([
                'price'     => $item->price * $item->quantity
            ]);
        }else{
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity'  => $request->quantity,
                'price'     => $product->price * $request->quantity,
                'size'      => $size
            ]);
        }

        return response()->json([
            'status'        => 200,
            'message'       => 'product has been added to cart',
        ] , 200);
    }
}
