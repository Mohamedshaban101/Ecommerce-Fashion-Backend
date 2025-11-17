<?php

namespace App\Http\Controllers\User;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LatestProductsController extends Controller
{
    public function __invoke(){
        $products = Product::orderBy('created_at' , 'ASC')->where('status' , 1)->limit(8)->with('product_images')->get();
        return response()->json([
            'status'        => 200,
            'data'          => $products
        ],200);
    }
}
