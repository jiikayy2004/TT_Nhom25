<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 

// API lấy thông tin user đang đăng nhập (Laravel có sẵn)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// --- API CỦA GYM APP ---

// API Đăng nhập (Frontend sẽ gọi vào link: http://localhost/QLPG/public/api/login)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);