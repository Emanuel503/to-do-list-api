<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TasksCategoriesController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\EnsureTaskBelongsToUser;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/unauthorized', [AuthController::class, 'unauthorized'])->name('unauthorized');

Route::middleware('auth:api')->group(function(){

    Route::prefix('users')->group(function(){
        Route::get('/', [UsersController::class, 'index']);
    });

    Route::prefix('tasks')->group(function () {
        Route::get('/', [TasksController::class, 'index']);
        Route::get('/{task}', [TasksController::class, 'show'])->middleware(EnsureTaskBelongsToUser::class);
        Route::post('/', [TasksController::class, 'store']);
        Route::put('/{task}', [TasksController::class, 'update'])->middleware(EnsureTaskBelongsToUser::class);
        Route::delete('/{task}', [TasksController::class, 'destroy'])->middleware(EnsureTaskBelongsToUser::class);
        Route::put('/restore/{task}', [TasksController::class, 'restore'])->middleware(EnsureTaskBelongsToUser::class);
        Route::post('/share/{task}/{user}', [TasksController::class, 'share'])->middleware(EnsureTaskBelongsToUser::class);
        Route::delete('/share/{task}/{user}', [TasksController::class, 'deleteShare'])->middleware(EnsureTaskBelongsToUser::class);
    });

    Route::prefix('categories')->group(function () {
        Route::get('/', [TasksCategoriesController::class, 'index']);
    });
});
