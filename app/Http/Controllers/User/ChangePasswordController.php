<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ChangePasswordController extends Controller
{
    public function __invoke(Request $request){
        $validator = Validator::make($request->all() , [
            'old_password' => ['required'],
            'password' => [
                'required' ,
                'confirmed',
                Password::min(8)->
                mixedCase()->
                letters()->
                numbers()->
                symbols()->
                uncompromised()
            ],
        ]);
        if($validator->fails()){
            return response()->json([
                'status'    => 422,
                'message'   => 'Validation Error',
                'error'     => $validator->errors()
            ]);
        }
        $user = User::where('id' , Auth::id())->first();
        if(!Hash::check($request->old_password , $user->password)){
            return response()->json([
                'status'    => 400,
                'message'   => 'old password is incorrect',
            ]);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json([
            'status'  => 200,
            'message' => 'Password changed successfully',
        ]);
    }
}
