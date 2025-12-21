<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    public function user()
    {
    return $this->belongsTo(User::class);
    }

    public function comments()
    {
    return $this->hasMany(Comment::class);
    }

    public function likes()
    {
    return $this->hasMany(Like::class);
    }

    // Pastikan fillable sesuai dengan tabel yang baru dibuat
    protected $fillable = [
        'user_id',
        'caption',
        'image', 
    ];
}