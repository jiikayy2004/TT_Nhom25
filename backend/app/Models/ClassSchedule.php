<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    protected $table = 'class_schedules';
    protected $fillable = ['class_name', 'trainer_id', 'start_time', 'end_time', 'max_members', 'current_members', 'description', 'is_active'];
    public $timestamps = true;

    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'trainer_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'schedule_id');
    }
}
