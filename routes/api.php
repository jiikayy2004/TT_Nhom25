<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\UserController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\BookingController; 
use App\Http\Controllers\DashboardController;


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

// --- API QUẢN LÝ ĐĂNG KÝ (SUBSCRIPTIONS) ---
Route::middleware('auth:sanctum')->group(function () {
    
Route::get('/subscriptions', [SubscriptionController::class, 'index']); // Xem danh sách

Route::post('/subscriptions', [SubscriptionController::class, 'store']); // Đăng ký mới
});

// --- API QUẢN LÝ ĐẶT LỊCH (BOOKINGS) ---
Route::middleware('auth:sanctum')->group(function () {
  
    Route::get('/bookings', [BookingController::class, 'index']);      // Xem danh sách lịch
    Route::post('/bookings', [BookingController::class, 'store']);     // Đặt lịch mới
    Route::put('/bookings/{id}', [BookingController::class, 'updateStatus']); // Duyệt lịch
});

// --- API THỐNG KÊ DASHBOARD ---
Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/dashboard-stats', [DashboardController::class, 'stats']);
});
