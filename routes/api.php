<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\UserController;
use App\Http\Controllers\PackageController;


// API lấy thông tin user đang đăng nhập (Laravel có sẵn)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// --- API CỦA GYM APP ---

// API Đăng nhập (Frontend sẽ gọi vào link: http://localhost/QLPG/public/api/login)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// --- API QUẢN LÝ MEMBER ---
Route::middleware('auth:sanctum')->group(function () {
    
    // API lấy danh sách user
    Route::get('/users', [UserController::class, 'index']);
    
    // API thêm user mới
    Route::post('/users', [UserController::class, 'store']);
    
    // API xóa user (truyền id vào link)
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    
});
// --- API QUẢN LÝ GÓI TẬP ---
Route::middleware('auth:sanctum')->group(function () {   
    // Lấy danh sách gói
    Route::get('/packages', [PackageController::class, 'index']);
    
    // Thêm gói mới
    Route::post('/packages', [PackageController::class, 'store']);
    
    // Xóa gói
    Route::delete('/packages/{id}', [PackageController::class, 'destroy']);
 });