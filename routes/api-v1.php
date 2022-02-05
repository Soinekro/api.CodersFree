<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Post\CategoryController;
use App\Http\Controllers\Api\Post\PostController;
use App\Http\Controllers\Api\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('user/register',[RegisterController::class,'store'])->name('api.v1.users.register');

Route::apiResource('post/categories',CategoryController::class)->names('api.v1.posts.categories');
Route::apiResource('post/posts',PostController::class)->names('api.v1.posts.posts');
Route::post('login',[LoginController::class,'store']);
