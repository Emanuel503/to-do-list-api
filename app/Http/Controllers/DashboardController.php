<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/dashboard",
     *     security={{"passport":{}}},
     *     tags={"Auth"},
     *     summary="Dashboard Admin return information of system",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=200),
     *             @OA\Property(property="message", type="string", example="Dashboard successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="count_users", type="integer", example="40"),
     *                 @OA\Property(property="count_tasks", type="integer", example="56")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=403),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="data", type="object", example=null),
     *         )
     *     )
     * )
     */
    public function dashboard()
    {
        $users = User::all();
        $tasks = Task::all();

        return response()->json([
            "code"      => 201,
            "message"   => "Dashboard successfully",
            "data"      => [
                'count_users'  => count($users),
                'count_tasks'  => count($tasks)
            ],
        ], 200);
    }
}
