<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    // Khai báo bảng trong database (nếu tên bảng không phải số nhiều chuẩn)
    protected $table = 'packages';

    // Cho phép lưu các cột này 
    protected $fillable = [
        'name',
        'price',
        'duration_days',
        'description',
    ];
}