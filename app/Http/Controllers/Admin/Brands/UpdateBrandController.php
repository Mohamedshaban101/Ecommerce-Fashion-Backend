<?php

namespace App\Http\Controllers\Admin\Brands;

use Exception;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UpdateBrandController extends Controller
{
    public function __invoke(Request $request , $id){
        $brand = Brand::find($id);
        if($brand == null){
            return response()->json([
                'status'    => true,
                'message'   => 'Brand Not Found',
                'data'      => []
            ] , 404);
        }
        $validator = Validator::make($request->all() , [
            'name'      => ['required' , 'string' , 'max:255'],
            'status'    => ['integer']
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => false,
                'message'   => 'Validation Error',
                'error'     => $validator->errors()
            ],422);
        }

        try {
            $validated = $validator->validated();

            $brand->update([
                'name'      => $validated['name'],
                'status'    => $validated['status']
            ]);
            return response()->json([
                'status'    => 200,
                'message'   => 'Brand Updated Successfully',
                'data'      => $brand
            ],200);
        } catch (Exception $e) {
            return response()->json([
                'status'    => false,
                'message'   => 'Error While Updating Brand',
                'error'     => $e->getMessage()
            ],500);
        }
    }
}
