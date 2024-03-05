<?php

use App\Http\Controllers\TasksController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('/user', UsersController::class);

Route::resource('/task', TasksController::class);