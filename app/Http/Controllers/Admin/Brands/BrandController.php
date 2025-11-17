<?php

namespace App\Http\Controllers\Admin\Brands;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    public function __invoke(){
        $brands = Brand::orderBy('created_at' , 'DESC')->get();
        return response()->json([
            'status' => 200,
            'data'   => $brands
        ]);
    }
}
