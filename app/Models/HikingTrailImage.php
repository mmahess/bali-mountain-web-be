<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HikingTrailImage extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function hikingTrail()
    {
        return $this->belongsTo(HikingTrail::class);
    }
}