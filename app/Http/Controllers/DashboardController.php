<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return response()->json([
            "code"      => 201,
            "message"   => "Dashboard successfully",
            "data"      => [
                'user'  => Auth::user(),
            ],
        ], 201);
    }
}
