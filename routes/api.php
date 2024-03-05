<?php

use App\Http\Controllers\TasksCategoriesController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('/users', UsersController::class);

Route::resource('/tasks', TasksController::class);

Route::resource('/categories', TasksCategoriesController::class);