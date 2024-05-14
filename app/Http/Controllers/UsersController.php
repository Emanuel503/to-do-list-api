<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function index(){
        $users = User::all();

        return response()->json([
            'code'      => 200,
            'message'   => 'List of users successfully',
            'data'      => [
                'users' => $users
            ]
        ]);
    }

    public function show($user){
        $user = User::find($user);

        return response()->json([
            'code'      => 200,
            'message'   => 'List of users successfully',
            'data'      => [
                'user' => $user
            ]
        ]);
    }

    public function store(Request $request){

        $rules = array(
            'email'         => 'required|string|email|unique:users',
            'password'      => 'required|string',
            'name'          => 'required|string',
            'rol'           => 'required|string|exists:roles,name',
            'image'         => 'image',
        );

        $messages = array(
            'email.required'        => 'email is required',
            'password.required'     => 'password is required',
            'name.required'         => 'name is required',
            'rol.required'          => 'rol is required',
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

        $user->assignRole($request->rol);

        return response()->json([
            "code"      => 201,
            "message"   => "User created successfully",
            "data"      => [
                'user'  => $user,
            ],
        ], 201);
    }
}
