<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TasksCategoriesController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/unauthorized', [AuthController::class, 'unauthorized'])->name('unauthorized');

Route::middleware('auth:api')->group(function(){
    Route::resource('/users', UsersController::class);

    Route::resource('/tasks', TasksController::class);
    Route::put('/tasks/restore/{id}', [TasksController::class, 'restore']);
    Route::post('/tasks/share/{idTask}/{idUser}', [TasksController::class, 'share']);
    Route::delete('/tasks/delete/shared/{id}', [TasksController::class, 'deleteShared']);

    Route::resource('/categories', TasksCategoriesController::class);
});
