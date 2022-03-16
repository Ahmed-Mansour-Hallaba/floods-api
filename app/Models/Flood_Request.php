<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flood_Request extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'requests';
    public function requested_by()
    {
        return $this->belongsTo(Civilian::class, 'civilian_id');
    }
    public function approved_by()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }
}
