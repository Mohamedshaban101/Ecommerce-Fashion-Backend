<?php

namespace App\Http\Controllers\Admin\Products;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeleteProductController extends Controller
{
    public function __invoke($id){
        $product = Product::find($id);
        if($product == null){
            return response()->json([
                'status'    => 404,
                'message'   => 'Product not found'
            ],404);
        }
        $images = $product->product_images;
        foreach($images as $img){
            $largeImage = public_path('uploads/products/large/'.$img->image);
            $smallImage = public_path('uploads/products/small/'.$img->image);
            if(file_exists($largeImage)){
                unlink($largeImage);
            }

            if(file_exists($smallImage)){
                unlink($smallImage);
            }
        }
        $product->delete();
        return response()->json([
            'status'        => 200,
            'message'       => 'Product Delete Successfully'
        ],200);
    }
}
