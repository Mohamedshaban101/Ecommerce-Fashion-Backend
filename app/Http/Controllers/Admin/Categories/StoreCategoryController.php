<?php

namespace App\Http\Controllers\Admin\Categories;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StoreCategoryController extends Controller
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
            $category = Category::create([
                'name'      => $validated['name'],
                'status'    => $validated['status'],
            ]);
            return response()->json([
                'status'    => 200,
                'message'   => 'Category Created Successfully',
                'data'      => $category
            ],200);
        } catch (Exception $e) {
            return response()->json([
                'status'    => false,
                'message'   => 'Error While Creating Category',
                'error'     => $e->getMessage(),
            ],403);
        }
    }
}
