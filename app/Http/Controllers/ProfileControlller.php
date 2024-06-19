<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

    /**
     * @OA\Post(
     *     path="/api/profile",
     *     security={{"passport":{}}},
     *     tags={"Profile"},
     *     summary="Update profile",
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
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Profile updated error",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="number", example=422),
     *             @OA\Property(property="message", type="string", example="Profile updated error"),
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
    public function update(Request $request){

        $user = User::find(Auth::user()->id);

        $rules = array(
            'email'         => 'required|string|email|unique:users,email,' . $user->id,
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
                "message"   => "Profile updated error",
                "data"      => [
                    "errors" => $validator->errors(),
                ],
            ], 422);
        }

        if ($request->hasFile('image')) {

            if ($user->image) {
                Storage::delete('public/images/' . $user->image);
            }

            $imagePath = $request->file('image')->store('public/images');
            $imageName = basename($imagePath);

            $user->image = $imageName;
        }

        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->password = Hash::make($request->password);
        $user->image    = $imageName ?? null;

        $user->save();

        return response()->json([
            "code"      => 201,
            "message"   => "Profile updated successfully",
            "data"      => $user,
        ], 201);
    }
}
