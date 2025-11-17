<?php

namespace App\Http\Controllers\Admin\Sizes;

use App\Models\Size;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SizeController extends Controller
{
    public function __invoke(){
        $sizes = Size::orderBy('name' , 'ASC')->get();
        if($sizes->isEmpty()){
            return response()->json([
                'status'    => 404,
                'data'      => []
            ],404);
        }
        return response()->json([
            'status'    => 200,
            'data'      => $sizes
        ],200);
    }
}
