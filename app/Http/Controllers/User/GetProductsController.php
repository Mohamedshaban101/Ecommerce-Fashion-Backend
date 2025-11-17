<?php

namespace App\Http\Controllers\User;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetProductsController extends Controller
{
    public function __invoke(Request $request){
        $products = Product::orderBy('created_at' , 'DESC')->where('status' , 1);

        if(!empty($request->category)){
            $products->whereIn('category_id' , (array) $request->category);
        }
        if(!empty($request->brand)){
            $products->whereIn('brand_id' , (array) $request->brand);
        }
        $products = $products->get();
        if($products == null){
            return response()->json([
                'status'    => 404,
                'data'      => [],
                'message'   => 'No data found'
            ] , 200);
        }
        return response()->json([
            'status' => 200,
            'data'  => $products
        ],200);
    }
}
