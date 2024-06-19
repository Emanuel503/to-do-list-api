<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Token;

/**
 * @OA\Info(
 *    title="API TASK",
 *    version="1.0.0",
 *    description="Rest Full API for the task application in which you can perform different actions depending on the user's role<br> <li>Task management</li> <li>User Management</li> <li>Role management</li> <li>User Registration</li> <br> API authentication works with bearer token",
 *    @OA\Contact(
 *         email="emanueljosemolina@gmail.com"
 *    )
 *  )
*/

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     security={},
     *     tags={"Auth"},
     *     summary="Register user and return a token",
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
     *                  @OA\Property(
     *                     property="image",
     *                     description="Image file to upload",
     *                     type="string",
     *                     format="binary"
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
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=401),
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
    public function register(Request $request)
    {
        $rules = array(
            'email'         => 'required|string|email|unique:users',
            'password'      => 'required|string',
            'name'          => 'required|string',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5000'
        );

        $messages = array(
            'email.required'        => 'email is required',
            'password.required'     => 'password is required',
            'name.required'         => 'name is required',
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
            $imagePath = $request->file('image')->store('public/images');
            $imageName = basename($imagePath);
        }

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'image'         => $imageName ?? null,
        ]);

        $user->assignRole('User');

        $credentials = request(['email', 'password']);
        Auth::attempt($credentials);

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        return response()->json([
            "code"      => 201,
            "message"   => "User created successfully",
            "data"      => [
                'user'  => $user,
                'access_token'  => $tokenResult->accessToken,
                'token_type'    => 'Bearer',
            ],
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     security={},
     *     tags={"Auth"},
     *     summary="Login user and return a token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="emanueljosemolina@gmail.com"),
     *             @OA\Property(property="password", type="string", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=200),
     *             @OA\Property(property="message", type="string", example="Login success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjo..."),
     *                 @OA\Property(property="token_type", type="string", example="Bearer"),
     *                 @OA\Property(property="user", type="object",
     *                       @OA\Property(property="id", type="integer", example="1"),
     *                       @OA\Property(property="name", type="string", example="Emanuel Molina"),
     *                       @OA\Property(property="email", type="string", example="emanueljosemolina@gmail.com"),
     *                       @OA\Property(property="email_verified_at", type="string", example="2024-05-14 19:38:33"),
     *                       @OA\Property(property="created_at", type="string", example="2024-05-14T19:38:33.000000Z"),
     *                       @OA\Property(property="updated_at", type="string", example="2024-05-14T19:38:33.000000Z"),
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=401),
     *             @OA\Property(property="message", type="string", example="Password or email is invalid"),
     *             @OA\Property(property="data", type="object", example=null),
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $rules = array(
            'email'         => 'required|string|email',
            'password'      => 'required|string',
            'remember_me'   => 'boolean'
        );

        $messages = array(
            'email.required'        => 'email is required',
            'password.required'     => 'password is required',
            'remember_me.boolean'   => 'remember_me is boolean'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials))
            return response()->json([
                'code'      => 401,
                'message'   => 'Password or email is invalid',
                'data'      => null
            ], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        return response()->json([
            'code'      => 200,
            'message'   => 'Login success',
            'data'      => [
                'access_token'  => $tokenResult->accessToken,
                'token_type'    => 'Bearer',
                'user'          => $user
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     security={{"passport":{}}},
     *     tags={"Auth"},
     *     summary="Logout user",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=200),
     *             @OA\Property(property="message", type="string", example="Login success"),
     *             @OA\Property(property="data", type="object", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
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
    public function logout()
    {
        $user = Auth::user();

        // Revocar todos los tokens de acceso del usuario
        $user->tokens->each(function (Token $token, $key) {
            $token->delete();
        });

        return response()->json([
            'code'      => 200,
            'message'   => 'Logout',
            'data'      => null
        ]);
    }

    public function unauthorized()
    {
        return response()->json([
            'code'      => 403,
            'message'   => 'Unauthorized',
            'data'      => null
        ], 403);
    }
}
