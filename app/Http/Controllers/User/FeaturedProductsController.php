<?php

namespace App\Http\Controllers\User;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeaturedProductsController extends Controller
{
    public function __invoke(){
        $products = Product::orderBy('created_at' , 'ASC')->where('is_featured' , 'yes')->with('product_images')->limit(8)->get();
        return response()->json([
            'status'        => 200,
            'data'          => $products
        ],200);
    }
}
