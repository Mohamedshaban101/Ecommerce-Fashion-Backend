<?php

namespace App\Http\Controllers\Admin\Products;

use App\Models\Size;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShowProductController extends Controller
{
    public function __invoke($id){
        $product = Product::with('product_images' , 'sizes')->find($id);
        $sizes = Size::all();
        if($product == null){
            return response()->json([
                'status'    => 404,
                'message'   => 'Product Not Found',
                'data'      => []
            ] , 404);
        }
        return response()->json([
            'status'    => 200,
            'data'      => $product,
            'sizes'     => $sizes
        ],200);
    }
}
