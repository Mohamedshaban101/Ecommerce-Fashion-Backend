<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleSocialiteController extends Controller
{
    public function redirectToGoogle(){
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback(){
        try{
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::updateOrCreate([
                'email' => $googleUser->getEmail(),
            ],[
                'name'          => $googleUser->getName(),
                'provider_id'   => $googleUser->getId(),
                'provider_name' => 'google',
                'password'      => Hash::make($googleUser->getName())
            ]);
            if(!$user->hasVerifiedEmail()){
                $user->sendEmailVerificationNotification();
            }
            $token = JWTAuth::fromUser($user);

            return redirect(env('FRONTEND_URL'))->cookie('token' , $token , 60*24*7 , '/' , '127.0.0.1' , false,true,false , 'Lax');
        }catch (Exception $e) {
            return redirect(env('FRONTEND_URL') . "/auth/failed");
        }
    }
}
