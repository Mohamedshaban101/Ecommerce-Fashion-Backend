<?php

namespace App\Http\Controllers\Cart;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipping;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\UserInformation;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function __invoke(Request $request){
        $validator = Validator::make($request->all() , [
            'name'      => ['required','string' , 'nullable'],
            'email'     => ['required','email' , 'nullable', Rule::unique('user_information' , 'email')->ignore(Auth::id())],
            'phone'     => ['required','string' , 'min:11' , 'max:15' , 'regex:/^\+?[0-9]{11,15}$/'],
            'address'   => ['required','string'],
            'city'      => ['required','string'],
            'state'     => ['required','string'],
            'zip'       => ['required','string']
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => 422,
                'message'   => 'Validation Error',
                'error'     => $validator->errors()
            ],422);
        }
        $cart = Cart::where('user_id' , Auth::id())->where('is_paid' , false)->first();
        if(!$cart || $cart->items->count() === 0){
            return response()->json([
                'status'    => 400,
                'message'   => 'Your cart is empty. Please add products before checkout'
            ]);
        }else{
            $subtotal = $cart->items->sum(function ($item){
                return $item->product->price * $item->quantity;
            });
            $shipping = Shipping::first();
            $amount = $shipping ? $shipping->shipping_charge : 0;
            $tax = round($subtotal * .04 , 2);
            $total = $subtotal + $amount + $tax;
            $userInfo = UserInformation::updateOrcreate([
                'user_id' => Auth::id()
            ],[
                'name'      => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'address'   => $request->address,
                'zip'       => $request->zip,
                'state'     => $request->state,
                'city'      => $request->city,
            ]);
            $order = Order::create([
                'user_id'   => Auth::id(),
                'subtotal'  => $subtotal,
                'total'     => $total,
                'shipping'  => $shipping->shipping_charge,
                'discount' => 0,
                'payment_method'   => $request->payment_method,
                'status'        => $request->status
            ]);
            foreach($cart->items as $item){
                $product = Product::where('id' , $item->product_id)->first();
                OrderItem::create([
                    'order_id'  => $order->id,
                    'product_id'=> $item->product_id,
                    'price'     => $item->price,
                    'quantity'  => $item->quantity,
                    'size'      => $item->size
                ]);
                $product->decrement('qty' , $item->quantity);
            }
            $cart->is_paid = true;
            $cart->save();
            return response()->json([
                'status'        => 200,
                'message'       => 'ckeckout successfully',
                'data'          => $order,
            ] , 200);
        }
    }
}
