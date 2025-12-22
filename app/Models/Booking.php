<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pt_id',
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

    // 2. Lấy thông tin PT (Huấn luyện viên)
    public function pt()
    {
        return $this->belongsTo(User::class, 'pt_id');
    }
    
}