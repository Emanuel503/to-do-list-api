<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $rules = array(
            'email'         => 'required|string|email|unique:users',
            'password'      => 'required|string',
            'name'          => 'required|string',
        );

        $messages = array(
            'email.required'        => 'email is required',
            'password.required'     => 'password is required',
            'name.required'         => 'name is required',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                "code"      => 422,
                "message"   => "User created error",
                "data"      => [
                    "errors" => $validator->errors(),
                ],
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            "code"      => 201,
            "message"   => "User created successfully",
            "data"      => [
                'user'  => $user,
            ],
        ], 201);
    }

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
            return response()->json([
                'code'      => 401,
                'message'   => 'Password or email is invalid',
                'data'      => null
            ], 200);

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        return response()->json([
            'code'      => 200,
            'message'   => 'Login success',
            'data'      => [
                'access_token'  => $tokenResult->accessToken,
                'token_type'    => 'Bearer',
                'user'          => $user
            ]
        ]);
    }

    public function unauthorized(){
        return response()->json([
            'code'      => 401,
            'message'   => 'Unauthorized',
            'data'      => null
        ], 401);
    }
}
