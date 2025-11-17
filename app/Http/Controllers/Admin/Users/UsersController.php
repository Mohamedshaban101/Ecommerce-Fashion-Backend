<?php

namespace App\Http\Controllers\Admin\Users;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function __invoke(){
        $users = User::orderBy('created_at' , 'ASC')->get();
        return response()->json([
            'status'        => 200,
            'data'          => $users
        ]);
    }
}
