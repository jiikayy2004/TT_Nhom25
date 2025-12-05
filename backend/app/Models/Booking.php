<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $fillable = ['member_id', 'schedule_id', 'trainer_id', 'start_time', 'end_time', 'type', 'status', 'notes'];
    public $timestamps = true;

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function schedule()
    {
        return $this->belongsTo(ClassSchedule::class, 'schedule_id');
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'trainer_id');
    }
}
