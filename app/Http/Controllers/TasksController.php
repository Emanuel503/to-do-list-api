<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TasksController extends Controller
{
    public function index(){
        $activeTasks = Task::where(['id_task_status' =>  1, 'user_id_register' => Auth::user()->id])->get();
        $hiddenTasks = Task::where(['id_task_status' =>  2, 'user_id_register' => Auth::user()->id])->get();

        $tasks = [
            'active' => $activeTasks,
            'hidden' => $hiddenTasks
        ];

        return response()->json([
            'code'      => 200,
            'message'   => 'List of tasks successfully',
            'data'      => [
                'tasks' => $tasks
            ]
        ]);
    }

    public function store(Request $request){

        $rules = array(
            'title'             => 'string|min:1',
            'description'       => 'string|min:1|required',
            'category'          => 'string|min:1',
            'color'             => 'string|min:6',
            'start_date'        => 'date|before:end_date',
            'end_date'          => 'date|after:start_date',
        );

        $messages = array(
            'description.required'      => 'description is required',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'code'      => 422,
                'meesage' => 'Task creation failed',
                'data'    => [
                    'errors' => $validator->errors()
                ]
            ], 422);
        }

        $task = new Task();

        $task->user_id_register = Auth::user()->id;
        $task->id_task_status   = 1;
        $task->title            = $request->title;
        $task->description      = $request->description;
        $task->category         = $request->category;
        $task->color            = $request->color;
        $task->start_date       = $request->start_date;
        $task->end_date         = $request->end_date;

        $task->save();

        return response()->json([
            "code"      => 200,
            "message"   => "Task created successfully",
            'data'      => [
                'task' => $task
            ]
        ]);
    }

    public function update(Request $request, $id){

        $rules = array(
            'title'             => 'string|min:1',
            'description'       => 'string|min:1|required',
            'category'          => 'string|min:1',
            'color'             => 'string|min:6',
            'start_date'        => 'date|before:end_date',
            'end_date'          => 'date|after:start_date',
            'id_task_status'    => Rule::in([1, 2]),
        );

        $messages = array(
            'description.required'      => 'description is required',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'code'      => 422,
                'meesage' => 'Task update failed',
                'data'    => [
                    'errors' => $validator->errors()
                ]
            ], 422);
        }

        $task = Task::find($id);

        if($task == null){
            return response()->json([
                'code'      => 404,
                'meesage' => 'Task update failed',
                'data'    => [
                    null
                ]
            ], 404);
        }

        $task->id_task_status   = $request->id_task_status ? $request->id_task_status : $task->id_task_status;;
        $task->title            = $request->title ? $request->title : $task->title;
        $task->description      = $request->description ? $request->description: $task->description;
        $task->category         = $request->category ? $request->category : $task->category;
        $task->color            = $request->color ? $request->color : $task->color;
        $task->start_date       = $request->start_date ? $request->start_date : $task->start_date;
        $task->end_date         = $request->end_date ? $request->end_date : $task->end_date;

        $task->save();

        return response()->json([
            "code"      => 200,
            "message"   => "Task updated successfully",
            'data'      => [
                'task' => $task
            ]
        ]);
    }

    public function destroy($id){

        $task = Task::find($id);

        if($task == null){
            return response()->json([
                'code'      => 404,
                'meesage' => 'Task deleted failed',
                'data'    => [
                    null
                ]
            ], 404);
        }

        $task->deleted_at       = Carbon::now();
        $task->id_task_status   = 3;

        $task->save();

        return response()->json([
            "code"      => 200,
            "message"   => "Task deleted successfully",
            'data'      => [
                'task' => $task
            ]
        ]);
    }

    public function restore($id){
        $task = Task::find($id);

        if($task == null){
            return response()->json([
                'code'      => 404,
                'meesage' => 'Task restored failed',
                'data'    => [
                    null
                ]
            ], 404);
        }

        $task->deleted_at       = null;
        $task->id_task_status   = 1;

        $task->save();

        return response()->json([
            "code"      => 200,
            "message"   => "Task restored successfully",
            'data'      => [
                'task' => $task
            ]
        ]);
    }
}
