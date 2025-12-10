<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HikingTrail;
use Illuminate\Http\Request;

class HikingTrailController extends Controller
{
    public function index()
    {
        $trails = HikingTrail::select(
            'id', 'name', 'slug', 'location', 'cover_image', 'difficulty_level', 'elevation'
        )->get();

        return response()->json([
            'message' => 'List data gunung berhasil diambil',
            'data' => $trails
        ]);
    }

    public function show($slug)
    {
        $trail = HikingTrail::where('slug', $slug)->firstOrFail();

        return response()->json([
            'message' => 'Detail gunung berhasil diambil',
            'data' => $trail
        ]);
    }
}