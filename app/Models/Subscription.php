<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model {
    use HasFactory;

    // Khai báo bảng trong database (nếu tên bảng không phải số nhiều chuẩn)
    protected $table = 'subscriptions';

    // Cho phép lưu các cột này 
    protected $fillable = [
        'user_id',
        'package_id',
        'start_date',
        'end_date',
        'status'
    ];
    // --- LIÊN KẾT DỮ LIỆU (RELATIONSHIPS) ---

    // 1. Một Đăng ký thuộc về một User
    // (Giúp ta lấy tên user dễ dàng: $subscription->user->name)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 2. Một Đăng ký thuộc về một Gói tập
    // (Giúp ta lấy tên gói: $subscription->package->name)
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}

