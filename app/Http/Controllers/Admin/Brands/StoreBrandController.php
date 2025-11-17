<?php

namespace App\Http\Controllers\Admin\Brands;

use Exception;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StoreBrandController extends Controller
{
    public function __invoke(Request $request){
        $validator = Validator::make($request->all() , [
            'name'      => ['required' , 'string' , 'max:255'],
            'status'    => ['integer']
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => false,
                'message'   => 'Validation Error',
                'error'     => $validator->errors()
            ]);
        }

        try {
            $validated = $validator->validated();
            $brand = Brand::create([
                'name'      => $validated['name'],
                'status'    => $validated['status'],
            ]);
            return response()->json([
                'status'    => 200,
                'message'   => 'Brand Created Successfully',
                'data'      => $brand
            ],200);
        } catch (Exception $e) {
            return response()->json([
                'status'    => false,
                'message'   => 'Error While Creating brand',
                'error'     => $e->getMessage(),
            ],403);
        }
    }
}
