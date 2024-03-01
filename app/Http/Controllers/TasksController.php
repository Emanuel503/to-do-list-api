<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TasksController extends Controller
{
    public function index(){
        $tasks = Task::all();

        return response()->json($tasks);
    }

    public function store(Request $request){

        $rules = array(
            'user_id_register'  => 'required',
            'description'       => 'required',
        );

        $messages = array(
            'user_id_register.required' => 'user_id_register is required',
            'description.required'      => 'description is required',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $task = new Task();

        $task->user_id_register = $request->user_id_register;
        $task->id_task_status   = 1;
        $task->title            = $request->title;
        $task->description      = $request->description;
        $task->category         = $request->category;
        $task->color            = $request->color;
        $task->start_date       = $request->start_date;
        $task->start_end        = $request->start_end;

        $task->save();

        return response()->json([
            "status"    => "success",
            "message"   => "Task created successfully",
            "task"      => $task,
        ]);
    }
}
