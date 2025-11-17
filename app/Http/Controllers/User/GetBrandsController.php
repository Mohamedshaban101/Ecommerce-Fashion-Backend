<?php

namespace App\Http\Controllers\User;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetBrandsController extends Controller
{
    public function __invoke(){
        $brands = Brand::orderBy('created_at' , 'ASC')->where('status' , 1)->get();
        return response()->json([
            'status' => 200,
            'data'   => $brands
        ],200);
    }
}
