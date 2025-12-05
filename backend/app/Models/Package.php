<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'packages';
    protected $fillable = ['code', 'name', 'description', 'price', 'period_days', 'sessions', 'is_active'];
    public $timestamps = true;

    public function members()
    {
        return $this->hasMany(Member::class, 'package_id');
    }
}
