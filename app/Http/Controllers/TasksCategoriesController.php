<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TasksCategoriesController extends Controller
{
    public function index(){

        $categories = Task::select('category', DB::raw('COUNT(*) as count'))
                                ->groupBy('category')
                                ->orderBy('category')
                                ->where(['user_id_register' => Auth::user()->id])
                                ->where('id_task_status', '<>', 3)
                                ->get();

        return response()->json([
            'code'      => 200,
            'message'   => 'List of categories successfully',
            'data'      => [
                'categories' => $categories
            ]
        ]);
    }
}
