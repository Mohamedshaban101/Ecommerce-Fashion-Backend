<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserInformation;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserInfoController extends Controller
{
    public function __invoke(Request $request){
        $validator = Validator::make($request->all() , [
            'name'      => ['string' , 'nullable'],
            'email'     => ['email' , 'nullable', Rule::unique('user_information' , 'email')->ignore(Auth::id())],
            'phone'     => ['required','string' , 'min:11' , 'max:15' , 'regex:/^\+?[0-9]{11,15}$/'],
            'address'   => ['required','string'],
            'city'      => ['required','string'],
            'state'     => ['required','string'],
            'zip'       => ['required','string']
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => 422,
                'message'   => 'Validation Error',
                'error'     => $validator->errors()
            ],422);
        }
        $user = User::where('id' , Auth::id())->first();
        
        $userInfo = UserInformation::updateOrcreate([
            'user_id' => Auth::id()
        ],[
            'name'      => $request->name ?? $user->name,
            'email'     => $request->email ?? $user->email,
            'phone'     => $request->phone,
            'address'   => $request->address,
            'zip'       => $request->zip,
            'state'     => $request->state,
            'city'      => $request->city,
        ]);
        return response()->json([
            'status'    => 200,
            'message'   => 'User Info Updated Successfully',
            'data'      => $userInfo
        ]);
    }
}
