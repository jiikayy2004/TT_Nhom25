<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Package;

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
}