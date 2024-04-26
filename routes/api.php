<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TasksCategoriesController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'], 'login');

Route::middleware('auth:api')->group(function(){
    Route::resource('/users', UsersController::class);
    Route::resource('/tasks', TasksController::class);
    Route::resource('/categories', TasksCategoriesController::class);
});


Route::get('unauthorized', function(){
    return response()->json([
       'message' => 'Unauthorized'
    ], 401);
})->name('api.unauthorized');