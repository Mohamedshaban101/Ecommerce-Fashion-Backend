<?php

namespace App\Http\Controllers\Admin\Shipping;

use App\Models\Shipping;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeleteShippingController extends Controller
{
    public function __invoke(){
        $shipping = Shipping::first();
        $shipping->delete();
        return response()->json([
            'status'        => 200,
            'message'       => 'Shipping Delete Successfully'
        ],200);
    }
}
