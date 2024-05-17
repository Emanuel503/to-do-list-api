<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     security={{"passport":{}}},
     *     tags={"Users"},
     *     summary="Get Users return a array of users",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=200),
     *             @OA\Property(property="message", type="string", example="List of users successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="users", type="array",
     *                     @OA\Items(type="object",
     *                           @OA\Property(property="id", type="integer", example=1),
     *                           @OA\Property(property="name", type="string", example="Emanuel Molina"),
     *                           @OA\Property(property="email", type="string", example="emanueljose@gmail.com"),
     *                           @OA\Property(property="image", type="string", example="public/images/KaUrisZTeapBQQPGROj2gQdWAqga4rXPtWPY6YQq.jpg"),
     *                           @OA\Property(property="created_at", type="string", example="2024-05-14T19:38:33.000000Z"),
     *                           @OA\Property(property="updated_at", type="string", example="2024-05-14T19:38:33.000000Z")
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
        $users = User::all();

        return response()->json([
            'code'      => 200,
            'message'   => 'List of users successfully',
            'data'      => [
                'users' => $users
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{user}",
     *     security={{"passport":{}}},
     *     tags={"Users"},
     *     summary="Get User return a user",
     *      @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the user to retrieve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=200),
     *             @OA\Property(property="message", type="string", example="List of users successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Emanuel Molina"),
     *                     @OA\Property(property="email", type="string", example="emanueljose@gmail.com"),
     *                     @OA\Property(property="image", type="string", example="public/images/KaUrisZTeapBQQPGROj2gQdWAqga4rXPtWPY6YQq.jpg"),
     *                     @OA\Property(property="created_at", type="string", example="2024-05-14T19:38:33.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", example="2024-05-14T19:38:33.000000Z")
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
    public function show($user){
        $user = User::find($user);

        return response()->json([
            'code'      => 200,
            'message'   => 'List of users successfully',
            'data'      => [
                'user' => $user
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     security={{"passport":{}}},
     *     tags={"Users"},
     *     summary="Register user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                  @OA\Property(
     *                     property="email",
     *                     description="Email of the user",
     *                     type="string",
     *                     example="emanueljose@gmail.com"
     *                 ),
     *                  @OA\Property(
     *                     property="password",
     *                     description="Password of the user",
     *                     type="string",
     *                     example="password"
     *                 ),
     *                  @OA\Property(
     *                     property="name",
     *                     description="Name of the user",
     *                     type="string",
     *                     example="Emanuel Molina"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     description="Image file to upload",
     *                     type="string",
     *                     format="binary"
     *                 ),
     *                 @OA\Property(
     *                     property="rol",
     *                     description="Role of the user",
     *                     type="string",
     *                     example="Admin"
     *                 )
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=201),
     *             @OA\Property(property="message", type="string", example="User created successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                       @OA\Property(property="id", type="integer", example="1"),
     *                       @OA\Property(property="name", type="string", example="Emanuel Molina"),
     *                       @OA\Property(property="email", type="string", example="emanueljosemolina@gmail.com"),
     *                       @OA\Property(property="email_verified_at", type="string", example="2024-05-14 19:38:33"),
     *                       @OA\Property(property="image", type="string", example="public/images/LVOfSH7m3NHo3g3nZ6dK2HYrk1t59Vn8FxHDeJxM.jpg"),
     *                       @OA\Property(property="created_at", type="string", example="2024-05-14T19:38:33.000000Z"),
     *                       @OA\Property(property="updated_at", type="string", example="2024-05-14T19:38:33.000000Z"),
     *                 ),
     *                 @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjo..."),
     *                 @OA\Property(property="token_type", type="string", example="Bearer")
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
     *             @OA\Property(property="message", type="string", example="Password or email is invalid"),
     *             @OA\Property(property="data", type="object", example=null),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="User created error",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=401),
     *             @OA\Property(property="message", type="string", example="Password or email is invalid"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="errors", type="object",
     *                     @OA\Property(property="email", type="array",
     *                        @OA\Items(type="string", example="The email has already been taken.")
     *
     *                     ),
     *                      @OA\Property(property="password", type="array",
     *                         @OA\Items(type="string", example="password is required.")
     *                     ),
     *                      @OA\Property(property="name", type="array",
     *                         @OA\Items(type="string", example="name is required.")
     *                     ),
     *                      @OA\Property(property="image", type="array",
     *                         @OA\Items(type="string", example={
     *                             "The image field must be an image.",
     *                             "The image field must be a file of type: jpg, jpeg, png, gif."
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
            'email'         => 'required|string|email|unique:users',
            'password'      => 'required|string',
            'name'          => 'required|string',
            'rol'           => 'required|string|exists:roles,name',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5000',
        );

        $messages = array(
            'email.required'        => 'email is required',
            'password.required'     => 'password is required',
            'name.required'         => 'name is required',
            'rol.required'          => 'rol is required',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                "code"      => 422,
                "message"   => "User created error",
                "data"      => [
                    "errors" => $validator->errors(),
                ],
            ], 422);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('public/images');
        }

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'image'         => $image ?? null,
        ]);

        $user->assignRole($request->rol);

        return response()->json([
            "code"      => 201,
            "message"   => "User created successfully",
            "data"      => [
                'user'  => $user,
            ],
        ], 201);
    }
}
