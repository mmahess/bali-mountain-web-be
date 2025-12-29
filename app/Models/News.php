<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    // Izinkan semua kolom diisi (Matikan proteksi mass assignment)
    protected $guarded = [];
    public function user()
    {
    return $this->belongsTo(User::class);
    }

    public function comments() {
    return $this->hasMany(NewsComment::class)->latest(); // Komentar terbaru di atas
}
}