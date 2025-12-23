<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Package;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function stats()
    {
        // 1. Đếm tổng thành viên (Chỉ đếm Member, không đếm Admin/PT)
        $totalMembers = User::where('role', 'member')->count();

        // 2. Đếm số gói đang hoạt động (active)
        $activeSubs = Subscription::where('status', 'active')->count();

        // 3. Tính tổng doanh thu
        // Logic: Lấy tất cả các lượt đăng ký -> Kèm theo thông tin gói -> Cộng dồn giá tiền
        $subscriptions = Subscription::with('package')->get();
        $revenue = $subscriptions->sum(function($sub) {
            return $sub->package ? $sub->package->price : 0;
        });

        // 4. Lấy 5 người đăng ký mới nhất (Để hiện ra bảng nhỏ)
        $recentSubs = Subscription::with(['user', 'package'])
                        ->orderBy('id', 'desc')
                        ->take(5)
                        ->get();

        return response()->json([
            'status' => true,
            'data' => [
                'total_members' => $totalMembers,
                'active_subs' => $activeSubs,
                'revenue' => $revenue,
                'recent_subs' => $recentSubs
            ]
        ]);
    }
    // --- HÀM MỚI: LẤY CHI TIẾT DOANH THU & GÓI ---
    public function getDetails(Request $request)
    {
        $type = $request->query('type');
        $data = [];

        if ($type == 'revenue') {
            // Lấy lịch sử doanh thu
            $data = \App\Models\Subscription::with(['user', 'package'])
                        ->orderBy('created_at', 'desc')->get();
        } elseif ($type == 'active_packages') {
            // Lấy gói đang chạy
            $today = \Carbon\Carbon::now();
            $data = \App\Models\Subscription::with(['user', 'package'])
                        ->where('end_date', '>=', $today)
                        ->orderBy('end_date', 'asc')->get();
        }

        return response()->json(['status' => true, 'data' => $data]);
    }
}