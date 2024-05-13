<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TasksCategoriesController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\EnsureTaskBelongsToUser;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/unauthorized', [AuthController::class, 'unauthorized'])->name('unauthorized');

Route::middleware('auth:api')->group(function(){

    Route::group(['middleware' => ['role:Admin']], function () {
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard.index');

        Route::prefix('users')->name('admin.users.')->group(function(){
            Route::get('/', [UsersController::class, 'index'])->name('index');
        });
    });

    Route::group(['middleware' => ['role:Admin|User',]], function () {
        Route::prefix('tasks')->name('user.tasks.')->group(function () {
            Route::get('/', [TasksController::class, 'index'])->name('index');
            Route::get('/{task}', [TasksController::class, 'show'])->middleware(EnsureTaskBelongsToUser::class)->name('show');
            Route::post('/', [TasksController::class, 'store'])->name('store');
            Route::put('/{task}', [TasksController::class, 'update'])->middleware(EnsureTaskBelongsToUser::class)->name('update');
            Route::delete('/{task}', [TasksController::class, 'destroy'])->middleware(EnsureTaskBelongsToUser::class)->name('destroy');
            Route::put('/restore/{task}', [TasksController::class, 'restore'])->middleware(EnsureTaskBelongsToUser::class)->name('restore');
            Route::post('/share/{task}/{user}', [TasksController::class, 'share'])->middleware(EnsureTaskBelongsToUser::class)->name('share');
            Route::delete('/share/{task}/{user}', [TasksController::class, 'deleteShare'])->middleware(EnsureTaskBelongsToUser::class)->name('deleteShare');
        });

        Route::prefix('categories')->name('user.categories.')->group(function () {
            Route::get('/', [TasksCategoriesController::class, 'index'])->name('index');
        });
    });
});
