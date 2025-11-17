<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\UserInformation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShowUserInfoController extends Controller
{
    public function __invoke(){
        $userInfo = UserInformation::where('id' , Auth::id())->first();

        if($userInfo == null){
            return response()->json([
                'status'    => 404,
                'data'      => null,
            ]);
        }
        return response()->json([
            'status'    => 200,
            'data'      => $userInfo
        ]);
    }
}
