<?php

namespace App\Http\Controllers\Admin\Products;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UpdateDefaultImage extends Controller
{
    public function __invoke(Request $request){
        $product = Product::find($request->product_id);
        $product->image = $request->image;
        $product->save();

        return response()->json([
            'status' => 200,
            'type'  => 'default_image_update',
            'message' => 'Image image changed successfully',
        ],200);
    }
}
