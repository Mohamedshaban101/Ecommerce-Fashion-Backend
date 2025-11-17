<?php

namespace App\Http\Controllers\Admin\Brands;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShowBrandController extends Controller
{
    public function __invoke($id){
        $brand = Brand::find($id);
        if($brand == null){
            return response()->json([
                'status'    => 404,
                'message'   => 'Brand Not Found',
                'data'      => []
            ] , 404);
        }
        return response()->json([
            'status'    => 200,
            'data'      => $brand
        ]);
    }
}
