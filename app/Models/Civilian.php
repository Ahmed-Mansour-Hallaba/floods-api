<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Civilian extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function user()
    {
        return $this->morphOne('App\Models\User', 'userable');
    }

    public function requests()
    {
        return $this->hasMany(Request::class, 'civilian_id', 'id');
    }

    public function location()
    {
        return $this->hasOne(UserLocation::class, 'civilian_id', 'id');
    }
}
