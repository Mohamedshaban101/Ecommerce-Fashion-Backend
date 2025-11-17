<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendResponse($message , $result = [] , $token = null, $code = 200) {
        $response = [
            'status'    => true,
            'message'   => $message,
            'token_type'=> 'Bearer'
        ];
        if(!is_null($token)){
            $response['token'] = $token;
        }
        if(!empty($result)){
            $response['data'] = $result;
        }
        return response()->json($response , $code);
    }

    public static function sendError($message , $errors = [] , $code = 403){
        $response = [
            'status'    => false,
            'message'   => $message
        ];
        if(!empty($errors)){
            $response['errors'] = $errors; 
        }
        return response()->json($response , $code);
    }
}
