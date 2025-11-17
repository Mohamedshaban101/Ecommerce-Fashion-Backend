<?php

namespace App\Http\Controllers\Auth;

use JWTException;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Auth\BaseController;

class LoginController extends BaseController
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'      => ['required', 'string'],
            'password'      => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

        try {
            $credentials = $request->only('email', 'password');
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->sendError('Ivalid credentials', [], 403);
            }
            $user = auth()->user();
            if (!$user->hasVerifiedEmail()) {
                $user->sendEmailVerificationNotification();
                $cookie = Cookie::forget('token' , '/' , '127.0.0.1');
                return response()->json([
                    'status'   => 403,
                    'message' => 'Please verify your email first.'
                ], 403)->withCookie($cookie);
            }
            $cookie = cookie('token', $token, 60 * 24 * 7, '/', '127.0.0.1', false, true, false, 'Lax');
            return response()->json([
                'status'        => 200,
                'message'       => 'User Login Successfully',
                'data'          => $user,
            ], 200)->withCookie($cookie);
        } catch (JWTException $e) {
            return $this->sendError('Could not create token', $e->getMessage());
        }
    }
}
