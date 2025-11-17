<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeleteCategoryController extends Controller
{
    public function __invoke($id){
        $category = Category::find($id);
        if($category === null){
            return response()->json([
                'status'    => 200,
                'message'   => 'Category Not Found',
                'data'      => []
            ] , 404);
        }
        $category->delete();
        return response()->json([
            'status'    => true,
            'message'   => 'Category Created Successfully',
        ],200);
    }
}
