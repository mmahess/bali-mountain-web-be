<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpenTrip extends Model
{
    public function user() {
    return $this->belongsTo(User::class);
    }
    public function hikingTrail() {
        return $this->belongsTo(HikingTrail::class);
    }
}
