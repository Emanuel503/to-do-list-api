<?php

namespace App\Http\Controllers;

use App\Models\SharedTask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TasksController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     security={{"passport":{}}},
     *     tags={"Tasks"},
     *     summary="Get list of tasks",
     *     description="Returns a list of tasks categorized by their status",
     *     @OA\Response(
     *         response=200,
     *         description="List of tasks successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=200),
     *             @OA\Property(property="message", type="string", example="List of tasks successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="tasks", type="object",
     *                     @OA\Property(property="active", type="array",
     *                         @OA\Items(type="object",
     *                             @OA\Property(property="id", type="integer", example=18),
     *                             @OA\Property(property="user_id_register", type="integer", example=1),
     *                             @OA\Property(property="id_task_status", type="integer", example=1),
     *                             @OA\Property(property="title", type="string", example="Aut quia quo non dolores omnis illo. In et ut exercitationem ipsam dolorem officia enim est."),
     *                             @OA\Property(property="description", type="string", example="Fuga maiores sed et quia et possimus. Voluptatem ratione inventore neque est dolores."),
     *                             @OA\Property(property="category", type="string", example=null),
     *                             @OA\Property(property="color", type="string", example="912141"),
     *                             @OA\Property(property="start_date", type="string", example=null),
     *                             @OA\Property(property="end_date", type="string", example=null),
     *                             @OA\Property(property="deleted_at", type="string", example=null),
     *                             @OA\Property(property="created_at", type="string", example="2024-05-17 17:59:26"),
     *                             @OA\Property(property="updated_at", type="string", example="2024-05-17 17:59:26"),
     *                             @OA\Property(property="user_name_register", type="string", example="Emanuel Molina")
     *                         )
     *                     ),
     *                     @OA\Property(property="hidden", type="array",
     *                         @OA\Items(type="object")
     *                     ),
     *                     @OA\Property(property="shared", type="array",
     *                         @OA\Items(type="object",
     *                             @OA\Property(property="id", type="integer", example=21),
     *                             @OA\Property(property="user_id_register", type="integer", example=5),
     *                             @OA\Property(property="id_task_status", type="integer", example=3),
     *                             @OA\Property(property="title", type="string", example="Aliquam quos illum nihil magni. Consequatur at alias quo. Illum et magni quibusdam totam."),
     *                             @OA\Property(property="description", type="string", example="Quae suscipit ducimus perspiciatis provident dolore deserunt sit enim. Maiores enim consequatur provident ullam sit. Doloremque magni quia aut voluptatem."),
     *                             @OA\Property(property="category", type="string", example="comida"),
     *                             @OA\Property(property="color", type="string", example="255869"),
     *                             @OA\Property(property="start_date", type="string", example=null),
     *                             @OA\Property(property="end_date", type="string", example=null),
     *                             @OA\Property(property="deleted_at", type="string", example=null),
     *                             @OA\Property(property="created_at", type="string", example="2024-05-17 17:59:26"),
     *                             @OA\Property(property="updated_at", type="string", example="2024-05-17 17:59:26"),
     *                             @OA\Property(property="user_name_register", type="string", example="Uriel Crona DDS")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Error: Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=403),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="data", type="object", example=null),
     *         )
     *     )
     * )
     */
    public function index(Request $request){

        $activeTasksDB = DB::table('tasks')
                        ->join('users', 'users.id', '=' ,'tasks.user_id_register')
                        ->select('tasks.*', 'users.name as user_name_register')
                        ->where(['id_task_status' => 1, 'user_id_register' => Auth::user()->id]);

        if ($request->get('category') != null) {
            $category = $request->get('category') === "null" ? null : $request->get('category');
            $activeTasksDB->where('category', $category);
        }

        $activeTasks = $activeTasksDB->get();

        $hiddenTasks = DB::table('tasks')
                        ->join('users', 'users.id', '=' ,'tasks.user_id_register')
                        ->select('tasks.*', 'users.name as user_name_register')
                        ->where(['id_task_status' => 2, 'user_id_register' => Auth::user()->id])
                        ->get();

        $sharedTasks = DB::table('shared_tasks')
                        ->join('tasks', 'tasks.id', '=' ,'shared_tasks.id_task')
                        ->join('users', 'users.id', '=' ,'tasks.user_id_register')
                        ->select('tasks.*', 'users.name as user_name_register')
                        ->where(['id_user' => Auth::user()->id])
                        ->get();

        $tasks = [
            'active' => $activeTasks,
            'hidden' => $hiddenTasks,
            'shared' => $sharedTasks
        ];

        return response()->json([
            'code'      => 200,
            'message'   => 'List of tasks successfully',
            'data'      => [
                'tasks' => $tasks
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/{task}",
     *     security={{"passport":{}}},
     *     tags={"Tasks"},
     *     summary="Get a tasks",
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the task to retrieve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=200),
     *             @OA\Property(property="message", type="string", example="List of tasks successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="task", type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="user_id_register", type="integer", example=1),
     *                      @OA\Property(property="id_task_status", type="integer", example=2),
     *                      @OA\Property(property="title", type="string", example="Itaque sequi dicta adipisci quia sed. Dolorem et eligendi illum at. Quo non voluptatem et pariatur."),
     *                      @OA\Property(property="description", type="string", example="Adipisci autem architecto natus aspernatur. Tempora dolorum dignissimos vel corrupti sapiente. Dignissimos quis labore neque qui placeat."),
     *                      @OA\Property(property="category", type="string", example="otros"),
     *                      @OA\Property(property="color", type="string", example="150524"),
     *                      @OA\Property(property="start_date", type="object", example=null),
     *                      @OA\Property(property="end_date", type="object", example=null),
     *                      @OA\Property(property="deleted_at", type="object", example=null),
     *                      @OA\Property(property="created_at", type="string", example="2024-05-14T19:38:33.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", example="2024-05-14T19:38:33.000000Z"),
     *                      @OA\Property(property="user_name_register", type="string", example="Emanuel Molina"),
     *                 )
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Error: Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=403),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="data", type="object", example=null),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=404),
     *             @OA\Property(property="message", type="string", example="Task not found"),
     *             @OA\Property(property="data", type="array",
     *                  @OA\Items(type="object", example=null)
     *             )
     *         )
     *     )
     * )
     */
    public function show($id){

        $task = DB::table('tasks')
                        ->join('users', 'users.id', '=' ,'tasks.user_id_register')
                        ->select('tasks.*', 'users.name as user_name_register')
                        ->where(['tasks.id' => $id])
                        ->first();

        return response()->json([
            'code'      => 200,
            'message'   => 'List of tasks successfully',
            'data'      => [
                'task' => $task
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     security={{"passport":{}}},
     *     tags={"Tasks"},
     *     summary="Save task",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Buy cat food"),
     *             @OA\Property(property="description", type="string", example="Buy two cat bags"),
     *             @OA\Property(property="category", type="string", example="Task"),
     *             @OA\Property(property="color", type="string", example="456456"),
     *             @OA\Property(property="start_date", type="string", example="2024/04/02"),
     *             @OA\Property(property="end_date", type="string", example="2024/04/05")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=201),
     *             @OA\Property(property="message", type="string", example="Task created successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="task", type="object",
     *                       @OA\Property(property="user_id_register", type="integer", example=1),
     *                       @OA\Property(property="id_task_status", type="integer", example=1),
     *                       @OA\Property(property="title", type="string", example="Buy cat food"),
     *                       @OA\Property(property="description", type="string", example="Buy two cat bags"),
     *                       @OA\Property(property="category", type="string", example="Task"),
     *                       @OA\Property(property="color", type="string", example="456456"),
     *                       @OA\Property(property="start_date", type="string", example="2024-05-20T19:35:00.000000Z"),
     *                       @OA\Property(property="end_date", type="string", example="2024-05-20T19:35:00.000000Z"),
     *                       @OA\Property(property="created_at", type="string", example="2024-05-14T19:38:33.000000Z"),
     *                       @OA\Property(property="updated_at", type="string", example="2024-05-14T19:38:33.000000Z"),
     *                       @OA\Property(property="id", type="integer", example="1"),
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Error: Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=403),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="data", type="object", example=null),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Task creation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=422),
     *             @OA\Property(property="message", type="string", example="Task creation failed"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="errors", type="object",
     *                     @OA\Property(property="description", type="array",
     *                        @OA\Items(type="string", example="description is required")
     *                     ),
     *                      @OA\Property(property="start_date", type="array",
     *                         @OA\Items(type="string", example={
     *                             "The start date field must be a valid date.",
     *                             "The start date field must be a date before end date."
     *                         })
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *  )
     */
    public function store(Request $request){

        $rules = array(
            'title'             => 'string|min:1|nullable',
            'description'       => 'string|min:1|required',
            'category'          => 'string|min:1|nullable',
            'color'             => 'string|min:6|max:6|nullable',
            'start_date'        => 'date|before:end_date|nullable',
            'end_date'          => 'date|after:start_date|nullable',
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
            "code"      => 201,
            "message"   => "Task created successfully",
            'data'      => [
                'task' => $task
            ]
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{task}",
     *     security={{"passport":{}}},
     *     tags={"Tasks"},
     *     summary="Update task",
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the task"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Buy cat food"),
     *             @OA\Property(property="description", type="string", example="Buy two cat bags"),
     *             @OA\Property(property="category", type="string", example="Task"),
     *             @OA\Property(property="color", type="string", example="456456"),
     *             @OA\Property(property="start_date", type="string", example="2024/04/02"),
     *             @OA\Property(property="end_date", type="string", example="2024/04/05")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=200),
     *             @OA\Property(property="message", type="string", example="Task updated successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="task", type="object",
     *                       @OA\Property(property="id", type="integer", example="1"),
     *                       @OA\Property(property="user_id_register", type="integer", example=1),
     *                       @OA\Property(property="id_task_status", type="integer", example=1),
     *                       @OA\Property(property="title", type="string", example="Buy cat food"),
     *                       @OA\Property(property="description", type="string", example="Buy two cat bags"),
     *                       @OA\Property(property="category", type="string", example="Task"),
     *                       @OA\Property(property="color", type="string", example="456456"),
     *                       @OA\Property(property="start_date", type="string", example="2024-05-20"),
     *                       @OA\Property(property="end_date", type="string", example="2024-05-20"),
     *                       @OA\Property(property="created_at", type="string", example="2024-05-14T19:38:33.000000Z"),
     *                       @OA\Property(property="updated_at", type="string", example="2024-05-14T19:38:33.000000Z"),
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Error: Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=403),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="data", type="object", example=null),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="intger", example=404),
     *             @OA\Property(property="message", type="string", example="Task not found"),
     *             @OA\Property(property="data", type="object", example=null),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Task creation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=422),
     *             @OA\Property(property="message", type="string", example="Task creation failed"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="errors", type="object",
     *                     @OA\Property(property="description", type="array",
     *                        @OA\Items(type="string", example="description is required")
     *                     ),
     *                      @OA\Property(property="start_date", type="array",
     *                         @OA\Items(type="string", example={
     *                             "The start date field must be a valid date.",
     *                             "The start date field must be a date before end date."
     *                         })
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *  )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/tasks/{task}",
     *     security={{"passport":{}}},
     *     tags={"Tasks"},
     *     summary="Delete task",
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the task"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=200),
     *             @OA\Property(property="message", type="string", example="Task deleted successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="task", type="object",
     *                       @OA\Property(property="id", type="integer", example="1"),
     *                       @OA\Property(property="user_id_register", type="integer", example=1),
     *                       @OA\Property(property="id_task_status", type="integer", example=1),
     *                       @OA\Property(property="title", type="string", example="Buy cat food"),
     *                       @OA\Property(property="description", type="string", example="Buy two cat bags"),
     *                       @OA\Property(property="category", type="string", example="Task"),
     *                       @OA\Property(property="color", type="string", example="456456"),
     *                       @OA\Property(property="start_date", type="string", example="2024-05-20"),
     *                       @OA\Property(property="end_date", type="string", example="2024-05-20"),
     *                       @OA\Property(property="deleted_at", type="string", example="2024-05-20T19:35:00.000000Z"),
     *                       @OA\Property(property="created_at", type="string", example="2024-05-14T19:38:33.000000Z"),
     *                       @OA\Property(property="updated_at", type="string", example="2024-05-14T19:38:33.000000Z"),
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Error: Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=403),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="data", type="object", example=null),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="intger", example=404),
     *             @OA\Property(property="message", type="string", example="Task not found"),
     *             @OA\Property(property="data", type="object", example=null),
     *         )
     *     ),
     *  )
     */
    public function destroy($id){

        $task = Task::find($id);

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

    /**
     * @OA\Put(
     *     path="/api/tasks/restore/{task}",
     *     security={{"passport":{}}},
     *     tags={"Tasks"},
     *     summary="Restore task",
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the task"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=200),
     *             @OA\Property(property="message", type="string", example="Task restored successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="task", type="object",
     *                       @OA\Property(property="id", type="integer", example="1"),
     *                       @OA\Property(property="user_id_register", type="integer", example=1),
     *                       @OA\Property(property="id_task_status", type="integer", example=1),
     *                       @OA\Property(property="title", type="string", example="Buy cat food"),
     *                       @OA\Property(property="description", type="string", example="Buy two cat bags"),
     *                       @OA\Property(property="category", type="string", example="Task"),
     *                       @OA\Property(property="color", type="string", example="456456"),
     *                       @OA\Property(property="start_date", type="string", example="2024-05-20"),
     *                       @OA\Property(property="end_date", type="string", example="2024-05-20"),
     *                       @OA\Property(property="deleted_at", type="object", example=null),
     *                       @OA\Property(property="created_at", type="string", example="2024-05-14T19:38:33.000000Z"),
     *                       @OA\Property(property="updated_at", type="string", example="2024-05-14T19:38:33.000000Z"),
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Error: Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=403),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="data", type="object", example=null),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="intger", example=404),
     *             @OA\Property(property="message", type="string", example="Task not found"),
     *             @OA\Property(property="data", type="object", example=null),
     *         )
     *     ),
     *  )
     */
    public function restore($id){
        $task = Task::find($id);

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

    /**
     * @OA\Post(
     *     path="/api/tasks/share/{task}/{user}",
     *     security={{"passport":{}}},
     *     tags={"Tasks"},
     *     summary="Save share task",
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the task"
     *     ),
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the user"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=200),
     *             @OA\Property(property="message", type="string", example="Task shared successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="task", type="object",
     *                       @OA\Property(property="id", type="integer", example="1"),
     *                       @OA\Property(property="user_id_register", type="integer", example=1),
     *                       @OA\Property(property="id_task_status", type="integer", example=1),
     *                       @OA\Property(property="title", type="string", example="Buy cat food"),
     *                       @OA\Property(property="description", type="string", example="Buy two cat bags"),
     *                       @OA\Property(property="category", type="string", example="Task"),
     *                       @OA\Property(property="color", type="string", example="456456"),
     *                       @OA\Property(property="start_date", type="string", example="2024-05-20"),
     *                       @OA\Property(property="end_date", type="string", example="2024-05-20"),
     *                       @OA\Property(property="deleted_at", type="object", example=null),
     *                       @OA\Property(property="created_at", type="string", example="2024-05-14T19:38:33.000000Z"),
     *                       @OA\Property(property="updated_at", type="string", example="2024-05-14T19:38:33.000000Z"),
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Error: Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=403),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="data", type="object", example=null),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task shared failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="intger", example=404),
     *             @OA\Property(property="message", type="string", example="Task shared failed"),
     *             @OA\Property(property="data", type="object", example=null),
     *         )
     *     ),
     *  )
     */
    public function share($task, $user){

        $task = Task::find($task);
        $user = User::find($user);

        if($user == null ||  $task == null){
            return response()->json([
                'code'      => 404,
                'meesage' => 'Task shared failed',
                'data'    => [
                    null
                ]
            ], 404);
        }

        $shared = SharedTask::where(['id_user' => $user->id, 'id_task' => $task->id])->first();

       if($shared == null){
            $shared = new SharedTask();

            $shared->id_user  = $user->id;
            $shared->id_task  = $task->id;

            $shared->save();
       }

        return response()->json([
            "code"      => 200,
            "message"   => "Task shared successfully",
            'data'      => [
                'task' => $task
            ]
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/tasks/share/{task}/{user}",
     *     security={{"passport":{}}},
     *     tags={"Tasks"},
     *     summary="Delete shared task",
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the task"
     *     ),
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the user"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=200),
     *             @OA\Property(property="message", type="string", example="Task shared deleted successfully"),
     *             @OA\Property(property="data", type="object", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Error: Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=403),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="data", type="object", example=null),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="intger", example=404),
     *             @OA\Property(property="message", type="string", example="Task not found"),
     *             @OA\Property(property="data", type="object", example=null),
     *         )
     *     ),
     *  )
     */
    public function deleteShare($task, $user){

        $shared = SharedTask::where(['id_user' => $user, 'id_task' => $task])->first();

        if($shared == null){
            return response()->json([
                'code'      => 404,
                'meesage' => 'Task shared deleted failed',
                'data'    => [
                    null
                ]
            ], 404);
        }

        $shared->delete();

        return response()->json([
            "code"      => 200,
            "message"   => "Task shared deleted successfully",
            'data'      => [
                null
            ]
        ]);
    }
}
