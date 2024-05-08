<?php

namespace App\Http\Middleware;

use App\Models\Task;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTaskBelongsToUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $taskId = $request->route('task');

        $task = Task::find($taskId);

        if (!$task) {
            return response()->json([
                'code'      => 404,
                'meesage' => 'Task not found',
                'data'    => [
                    null
                ]
            ], 404);
        }

        if($task->user_id_register !== Auth::user()->id){
            return redirect('api/unauthorized');
        }

        return $next($request);
    }
}
