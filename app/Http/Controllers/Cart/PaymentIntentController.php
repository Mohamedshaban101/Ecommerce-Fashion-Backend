<?php

namespace App\Http\Controllers\Cart;

use Stripe\Stripe;
use App\Models\Cart;
use App\Models\Shipping;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PaymentIntentController extends Controller
{
    public function __invoke(Request $request)
    {
        $cart = Cart::where('user_id' , Auth::id())->where('is_paid' , false)->first();
        if(!$cart || $cart->items->count() === 0){
            return response()->json([
                'status'    => 400,
                'message'   => 'Your cart is empty. Please add products before checkout'
            ]);
        }else{
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $paymentIntent = PaymentIntent::create([
                'amount'    => $request->amount * 100,
                'currency'  => 'USD',
                'description'   => 'payment from user id: ' . Auth::id(),
                'metadata'  => ['user_id' => Auth::id()],
                'payment_method_types' => ['card']
            ]);
            $clientSecret=$paymentIntent->client_secret;
            return response()->json([
                'status'    => 200,
                'clientSecret'     => $clientSecret
            ]);
        }
    }
}
