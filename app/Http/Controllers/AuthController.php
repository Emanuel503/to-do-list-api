<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request){
        $rules = array(
            'email'         => 'required|string|email',
            'password'      => 'required|string',
            'remember_me'   => 'boolean'
        );

        $messages = array(
            'email.required'        => 'email is required',
            'password.required'     => 'password is required',
            'remember_me.boolean'   => 'remember_me is boolean'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials))
            return response()->json(['message' => 'Unauthorized'], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
        ]);
    }
}
