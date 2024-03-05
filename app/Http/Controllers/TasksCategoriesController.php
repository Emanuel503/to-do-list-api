<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TasksCategoriesController extends Controller
{
    public function index(){

        $categories = Task::select('category', DB::raw('COUNT(*) as count'))
                                ->groupBy('category')
                                ->get();

        return response()->json($categories);
    }
}
