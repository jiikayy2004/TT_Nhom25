<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $table = 'trainers';
    protected $fillable = ['user_id', 'specialty', 'bio', 'hourly_rate', 'available_schedule', 'is_active'];
    protected $casts = ['available_schedule' => 'array'];
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function schedules()
    {
        return $this->hasMany(ClassSchedule::class, 'trainer_id');
    }
}
