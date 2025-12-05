<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/health', function () {
    return response()->json(['status' => 'OK']);
});

Route::middleware('api')->group(function () {
    // Auth routes (no token required)
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::get('/auth/check-email', [AuthController::class, 'checkEmail']);
    Route::get('/auth/check-username', [AuthController::class, 'checkUsername']);

    // Protected routes (require auth middleware)
    Route::middleware('auth:api')->group(function () {
        Route::get('/auth/profile', [AuthController::class, 'profile']);
    });
});