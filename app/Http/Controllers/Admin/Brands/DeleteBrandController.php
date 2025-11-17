<?php

namespace App\Http\Controllers\Admin\Brands;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeleteBrandController extends Controller
{
    public function __invoke($id){
        $brand = Brand::find($id);
        if($brand === null){
            return response()->json([
                'status'    => 200,
                'message'   => 'Brand Not Found',
                'data'      => []
            ] , 404);
        }
        $brand->delete();
        return response()->json([
            'status'    => true,
            'message'   => 'Brand Created Successfully',
        ],200);
    }
}
