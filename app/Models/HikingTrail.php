<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HikingTrail extends Model
{
    use HasFactory;

    protected $guarded = []; 

    // Relasi ke Gambar Galeri
    public function images()
    {
        return $this->hasMany(HikingTrailImage::class);
    }

    // Relasi ke Review
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    // Relasi ke Open Trip (Opsional, untuk masa depan)
    public function openTrips()
    {
        return $this->hasMany(OpenTrip::class);
    }
}