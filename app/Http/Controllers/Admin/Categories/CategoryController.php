<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function __invoke(){
        $categories = Category::orderBy('created_at' , 'DESC')->get();
        return response()->json([
            'status' => 200,
            'data'   => $categories
        ],200);
    }
}
