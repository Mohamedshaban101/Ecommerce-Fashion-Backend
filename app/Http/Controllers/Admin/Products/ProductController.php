<?php

namespace App\Http\Controllers\Admin\Products;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function __invoke(){
        try {
            $products = Product::orderBy('created_at' , 'DESC')->with('product_images')->get();

        if($products->isEmpty()){
            return response()->json([
                'status'    => 404,
                'message'   => 'Product Not Found',
                'data'      => []
            ]);
        }

        return response()->json([
            'status'    => 200,
            'data'      => $products
        ]);
        } catch (Exception $e) {
            return response()->json([
                'status'    => false,
                'message'   => 'Error While Loop The Products',
                'error'     => $e->getMessage()
            ],403);
        }
    }
}
