<?php

namespace App\Http\Controllers\Admin\Shipping;

use App\Models\Shipping;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StoreShippingController extends Controller
{
    public function __invoke(Request $request){
        $validator = Validator::make($request->all() , [
            'shipping_charge' => ['required' , 'numeric']
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => 422,
                'message'   => 'Validation Error',
                'error'     => $validator->errors()
            ]);
        }
        $shipping = Shipping::first();
        if($shipping){
            $shipping->update([
                'shipping_charge' => $request->shipping_charge
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Shipping charge updated successfully',
                'data' => $shipping
            ]);
        }
        $shipping = Shipping::create([
            'shipping_charge' => $request->shipping_charge
        ]);

        return response()->json([
            'status'    => 200,
            'message'   => 'Shipping charge created successfully',
            'data'      => $shipping
        ],200);
    }
}
