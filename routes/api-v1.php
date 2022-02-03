<?php

use App\Http\Controllers\Api\User\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('user/register',[RegisterController::class,'store'])->name('api.v1.user.register');
