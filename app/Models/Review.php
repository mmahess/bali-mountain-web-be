<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'hiking_trail_id', 'rating', 'comment'];

    // Review milik siapa?
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Review untuk gunung apa?
    public function hikingTrail()
    {
        return $this->belongsTo(HikingTrail::class);
    }
}