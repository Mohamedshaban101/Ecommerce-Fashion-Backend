<?php

namespace App\Http\Controllers\Auth;

use JWTException;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\Auth\BaseController;

class RegisterController extends BaseController
{
    public function __invoke(Request $request){
        $validator = Validator::make($request->all() , [
            'name'      => ['required' , 'string' , 'max:255'],
            'email'         => ['required' , 'email' , 'max:255','unique:users'],
            'password'      => [
                'required' ,
                'confirmed' ,
                Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised(),     
            ],
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error' , $validator->errors());
        }

        try {
            $validated = $validator->validated();
            $user = User::create([
                'name'  => $validated['name'],
                'email'     => $validated['email'],
                'password'  => Hash::make($validated['password']),
            ]);
            if(!$user->hasVerifiedEmail()){
                $user->sendEmailVerificationNotification();
            }
            $token = JWTAuth::fromUser($user);
            $cookie = cookie('token',$token,60 * 24 * 7 , '/' , '127.0.0.1' ,true,true,false,'None');
            return response()->json([
                'status'        => 200,
                'message'       => 'User Register Successfully',
            ],200)->withCookie($cookie);
        } catch (JWTException $e) {
            return $this->sendError('Token Creation Error' , $e->getMessage());
        }
    }
}

// composer require tymon/jwt-auth
// php artisan vendor:publish --provider="Tymon/JWTAuth/Providers/LaravelServiceProvider";
// php artisan jwt:secret