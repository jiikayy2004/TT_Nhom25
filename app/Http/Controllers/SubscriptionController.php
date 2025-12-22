<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Package;
use Carbon\Carbon; // thu vien xu ly ngay thang

class SubscriptionController extends Controller
{
    // --- 1. LẤY DANH SÁCH ĐĂNG KÝ ---
    public function index()
    {
        // Lấy tất cả, nhưng kẹp thêm thông tin User và Package đi cùng (with)
        // orderBy('id', 'desc'): Cái nào mới đăng ký thì hiện lên đầu
        $subs = Subscription::with(['user', 'package'])
                    ->orderBy('id', 'desc')
                    ->get();

        return response()->json([
            'status' => true,
            'data' => $subs
        ]);
    }

    // --- 2. ĐĂNG KÝ GÓI MỚI  ---
    public function store(Request $request)
    {
        // 1. Validate dữ liệu
        $request->validate([
            'user_id' => 'required|exists:users,id',       // User phải tồn tại trong bảng users
            'package_id' => 'required|exists:packages,id', // Gói phải tồn tại
        ]);

        // 2. Tìm thông tin gói tập để biết nó dài bao nhiêu ngày
        $package = Package::find($request->package_id);

        // 3. Tính toán ngày
        $startDate = Carbon::now(); // Ngày bắt đầu là hôm nay
        // Ngày hết hạn = Hôm nay + Số ngày của gói
        $endDate = Carbon::now()->addDays($package->duration_days); 

        // 4. Lưu vào Database
        $sub = Subscription::create([
            'user_id' => $request->user_id,
            'package_id' => $request->package_id,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'status' => 'active'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Đăng ký gói thành công!',
            'data' => $sub
        ]);
    }
}
