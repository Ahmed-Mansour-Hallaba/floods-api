<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flood extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function added_by()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }
}
