<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pt_id',
        'schedule_id',
        'package_id', // <--- Đã thêm trường này để lưu Gói tập
        'schedule_time',
        'status',
        'note'
    ];

    // --- LIÊN KẾT DỮ LIỆU ---

    // 1. Lấy thông tin khách hàng đặt lịch
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 2. Lấy thông tin PT (Huấn luyện viên) - Dùng cho đặt lịch 1 kèm 1
    public function pt()
    {
        return $this->belongsTo(User::class, 'pt_id');
    }

    // 3. Lấy thông tin Gói tập
    public function package() {
        return $this->belongsTo(Package::class, 'package_id');
    }

    // 4. Lấy thông tin Lớp học (Schedule) - Dùng cho đặt lịch theo lớp
    // QUAN TRỌNG: Đây là hàm giúp sửa lỗi "Đang tải..." ở Dashboard
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }
}