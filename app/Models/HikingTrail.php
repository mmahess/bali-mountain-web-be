<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HikingTrail extends Model
{
        public function images()
    {
        return $this->hasMany(HikingTrailImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
