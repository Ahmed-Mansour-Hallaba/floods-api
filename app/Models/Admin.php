<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function user()
    {
        return $this->morphOne('App\Models\User', 'userable');
    }

    public function floods()
    {
        return $this->hasMany(Flood::class, 'added_by', 'id');
    }

    public function requests()
    {
        return $this->hasMany(Request::class, 'approved_by', 'id');
    }

}
