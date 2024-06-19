<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileControlller extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/profile",
     *     security={{"passport":{}}},
     *     tags={"Profile"},
     *     summary="Get profile of login user",
     *     description="Returns a information of login user",
     *     @OA\Response(
     *         response=200,
     *         description="Profile successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=200),
     *             @OA\Property(property="message", type="string", example="Profile successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Emanuel Molina"),
     *                 @OA\Property(property="email", type="string", example="emanueljosemolina@gmail.com"),
     *                 @OA\Property(property="email_verified_at", type="object", example=null),
     *                 @OA\Property(property="image", type="string", example="I7BtUuRAl4DnbpkmVeLBuZIdvsx4KKtf7XnaC2W6.jpg"),
     *                 @OA\Property(property="created_at", type="string", example="2024-05-17T17:59:26.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2024-05-17T17:59:26.000000Z"),
     *                 @OA\Property(property="roles", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="string", example=1),
     *                         @OA\Property(property="name", type="string", example="Admin"),
     *                         @OA\Property(property="guard_name", type="string", example="api"),
     *                         @OA\Property(property="created_at", type="string", example="2024-05-17T17:59:25.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", example="2024-05-17T17:59:25.000000Z"),
     *                         @OA\Property(property="pivot", type="object",
     *                              @OA\Property(property="model_type", type="string", example="App\\Models\\User"),
     *                              @OA\Property(property="model_id", type="number", example=1),
     *                              @OA\Property(property="role_id", type="number", example=1),
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
    public function show(){
        $user = Auth::user();
        return response()->json([
            'code'      => 200,
            'message'   => 'Profile successfully',
            'data'      => $user
        ]);
    }
}
