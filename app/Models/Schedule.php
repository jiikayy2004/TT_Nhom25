<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    // --- THÊM ĐOẠN NÀY VÀO ---
    protected $fillable = [
        'name',
        'type',
        'trainer',
        'start_time',
        'end_time',
        'max_slots',
        'booked_slots',
    ];
}