<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenTrip extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    // Relasi Leader
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi Gunung
    public function hikingTrail()
    {
        return $this->belongsTo(HikingTrail::class);
    }

    // --- TAMBAHKAN INI (RELASI PESERTA) ---
    public function participants()
    {
        return $this->belongsToMany(User::class, 'trip_participants', 'open_trip_id', 'user_id')
                    ->withTimestamps();
    }
    
    // Agar jumlah peserta otomatis terhitung saat mengambil data
    protected $withCount = ['participants'];
}