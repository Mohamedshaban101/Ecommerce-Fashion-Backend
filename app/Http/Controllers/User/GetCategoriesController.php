<?php

namespace App\Http\Controllers\User;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetCategoriesController extends Controller
{
    public function __invoke(){
        $categories = Category::orderBy('created_at' , 'ASC')->where('status' , 1)->get();
        return response()->json([
            'status' => 200,
            'data'   => $categories
        ],200);
    }
}
