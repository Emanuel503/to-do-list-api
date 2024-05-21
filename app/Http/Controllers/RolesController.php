<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Contracts\Role;

class RolesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/roles",
     *     security={{"passport":{}}},
     *     tags={"Roles"},
     *     summary="Get list of roles",
     *     description="Returns a list of roles",
     *     @OA\Response(
     *         response=200,
     *         description="List of roles successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=200),
     *             @OA\Property(property="message", type="string", example="List of roles successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="roles", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Admin"),
     *                         @OA\Property(property="guard_name", type="string", example="api"),
     *                         @OA\Property(property="created_at", type="string", example="2024-05-17 17:59:26"),
     *                         @OA\Property(property="updated_at", type="string", example="2024-05-17 17:59:26"),
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
    public function index(){

        $roles = DB::table('roles')->get();

        return response()->json([
            'code'      => 200,
            'message'   => 'List of roles successfully',
            'data'      => [
                'roles' => $roles
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/roles/{rol}",
     *     security={{"passport":{}}},
     *     tags={"Roles"},
     *     summary="Get a rol",
     *     @OA\Parameter(
     *         name="rol",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the rol to retrieve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=200),
     *             @OA\Property(property="message", type="string", example="List of rol successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="rol", type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Admin"),
     *                      @OA\Property(property="guard_name", type="string", example="api"),
     *                      @OA\Property(property="created_at", type="string", example="2024-05-17 17:59:25"),
     *                      @OA\Property(property="updated_at", type="string", example="2024-05-17 17:59:25")
     *                 ),
     *                 @OA\Property(property="permissions", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
        *                      @OA\Property(property="name", type="string", example="admin.dashboard.index"),
        *                      @OA\Property(property="guard_name", type="string", example="api"),
        *                      @OA\Property(property="created_at", type="string", example="2024-05-17 17:59:25"),
        *                      @OA\Property(property="updated_at", type="string", example="2024-05-17 17:59:25")
     *                    )
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
     *         description="Rol not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=404),
     *             @OA\Property(property="message", type="string", example="Rol not found"),
     *             @OA\Property(property="data", type="object", example=null),
     *         )
     *     )
     * )
     */
    public function show($rol){

        $objRol = DB::table('roles')
                    ->where('roles.id', '=', $rol)
                    ->first();

        if($objRol == null){
            return response()->json([
                'code'      => 404,
                'message'   => 'Rol not found',
                'data'      => null
            ], 404);
        }

        $permissions = DB::table('role_has_permissions')
                        ->select(['permissions.*'])
                        ->join('permissions', 'permissions.id', '=' ,'role_has_permissions.permission_id')
                        ->where('role_has_permissions.role_id', '=', $rol)
                        ->get();

        return response()->json([
            'code'      => 200,
            'message'   => 'List of rol successfully',
            'data'      => [
                'rol'           => $objRol,
                'permissions'   => $permissions
            ]
        ]);
    }
}
