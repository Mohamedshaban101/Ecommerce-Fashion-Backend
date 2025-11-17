<?php

namespace App\Http\Controllers\Admin\Categories;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UpdateCategoryController extends Controller
{
    public function __invoke(Request $request , $id){
        $category = Category::find($id);
        if($category == null){
            return response()->json([
                'status'    => true,
                'message'   => 'Category Not Found',
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

            $category->update([
                'name'      => $validated['name'],
                'status'    => $validated['status']
            ]);
            return response()->json([
                'status'    => 200,
                'message'   => 'Category Updated Successfully',
                'data'      => $category
            ],200);
        } catch (Exception $e) {
            return response()->json([
                'status'    => false,
                'message'   => 'Error While Updating',
                'error'     => $e->getMessage()
            ],500);
        }
    }
}
