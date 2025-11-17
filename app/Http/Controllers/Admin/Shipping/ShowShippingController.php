<?php

namespace App\Http\Controllers\Admin\Shipping;

use App\Models\Shipping;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShowShippingController extends Controller
{
    public function __invoke(){
        $shipping = Shipping::first();
        return response()->json([
            'status'        => 200,
            'data'          => $shipping
        ]);
    }
}
