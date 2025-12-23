<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// 1. IMPORT CONTROLLERS (Đảm bảo file Controller tồn tại trong app/Http/Controllers)
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\UserController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\BookingController; 
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. KHU VỰC CÔNG KHAI (Public Routes)
// ==========================================
// Các API này KHÔNG YÊU CẦU Token (Ai cũng gọi được)

// Đăng nhập & Đăng ký
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Lấy danh sách gói tập (Để hiển thị ở Trang Chủ)
Route::get('/packages', [PackageController::class, 'index']); 


// ==========================================
// 2. KHU VỰC BẢO MẬT (Protected Routes)
// ==========================================
// Các API này BẮT BUỘC phải có Token (Bearer ...)

Route::middleware('auth:sanctum')->group(function () {

    // --- LẤY THÔNG TIN USER ĐANG ĐĂNG NHẬP ---
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/user/update', [UserController::class, 'updateProfile']);
    // --- QUẢN LÝ MEMBER (USERS) ---
    Route::get('/users', [UserController::class, 'index']); // Xem DS
    Route::post('/users', [UserController::class, 'store']); // Thêm
    Route::delete('/users/{id}', [UserController::class, 'destroy']); // Xóa

    // --- QUẢN LÝ GÓI TẬP (ADMIN) ---
    // (Lưu ý: Route xem gói GET /packages đã đưa ra public rồi)
    Route::post('/packages', [PackageController::class, 'store']); // Thêm gói
    Route::put('/packages/{id}', [PackageController::class, 'update']); // Sửa gói  
    Route::delete('/packages/{id}', [PackageController::class, 'destroy']); // Xóa gói

    // --- QUẢN LÝ ĐĂNG KÝ (SUBSCRIPTIONS) ---
    Route::get('/subscriptions', [SubscriptionController::class, 'index']); // Xem DS
    Route::post('/subscriptions', [SubscriptionController::class, 'store']); // Đăng ký mua gói
    Route::delete('/subscriptions/{id}', [SubscriptionController::class, 'destroy']); // Xóa

    // --- QUẢN LÝ ĐẶT LỊCH (BOOKINGS) ---
    Route::get('/bookings', [BookingController::class, 'index']); // Xem lịch
    Route::post('/bookings', [BookingController::class, 'store']); // Đặt lịch
    Route::put('/bookings/{id}', [BookingController::class, 'updateStatus']); // Duyệt lịch

    // --- THỐNG KÊ DASHBOARD ---
    Route::get('/dashboard-stats', [DashboardController::class, 'stats']);

    // --- THANH TOÁN ---
    Route::post('/payment/create', [PaymentController::class, 'createPayment']);

    // Route để khách đăng ký lớp
    Route::post('/book-class', [App\Http\Controllers\ScheduleController::class, 'bookClass']);
    // Route đặt lịch (Ghi đè hoặc thêm mới)
    Route::post('/bookings', [App\Http\Controllers\ScheduleController::class, 'bookClass']);
    // Route cho Schedule
    Route::get('/schedules', [App\Http\Controllers\ScheduleController::class, 'index']); // Lấy danh sách
    Route::post('/schedules', [App\Http\Controllers\ScheduleController::class, 'store']); // Tạo mới
    Route::delete('/schedules/{id}', [App\Http\Controllers\ScheduleController::class, 'destroy']); // Xóa

    // Route xem danh sách thành viên trong lớp
Route::get('/schedules/{id}/members', [App\Http\Controllers\ScheduleController::class, 'getMembers']);
// API lấy thống kê tổng quan (cũ)
    Route::get('/dashboard-stats', [DashboardController::class, 'stats']);
    
    // API LẤY CHI TIẾT (MỚI)
   Route::get('/dashboard-details', [DashboardController::class, 'getDetails']);
});