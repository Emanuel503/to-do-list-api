<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TasksCategoriesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     security={{"passport":{}}},
     *     tags={"Categories"},
     *     summary="Get list of categories",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=200),
     *             @OA\Property(property="message", type="string", example="List of categories successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="categories", type="array",
     *                     @OA\Items(type="object",
     *                           @OA\Property(property="category", type="integer", example="Comida"),
     *                           @OA\Property(property="count", type="integer", example=1),
     *                      )
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
     *     )
     * )
     */
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
