<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'phone', 'username', 'password', 'role', 'is_active'];
    protected $hidden = ['password'];
    public $timestamps = true;

    public function member()
    {
        return $this->hasOne(Member::class, 'user_id');
    }

    public function trainer()
    {
        return $this->hasOne(Trainer::class, 'user_id');
    }
}
