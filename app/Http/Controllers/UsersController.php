<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(){
        $users = User::all();

        return response()->json($users);
    }

    public function store(Request $request){
        
        return response()->json($request);
    }
}
