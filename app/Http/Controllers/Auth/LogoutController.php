<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Auth\BaseController;

class LogoutController extends BaseController
{
    public function __invoke(){
        try {
            $cookie = Cookie::forget('token' , '/' , '127.0.0.1');
            return response()->json([
                'status' => 200,
                'message' => 'Logged out successfully',
            ],200)->withCookie($cookie);
        } catch (JWTException $e) {
            return $this->sendError('failed to logout' , $e->getMessage());
        }
    }
}
