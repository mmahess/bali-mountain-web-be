<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OpenTrip;
use App\Models\CommunityPost;
use App\Models\HikingTrail;
use App\Models\News;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Ambil Data Gunung (Limit 4 untuk Popular Section)
        $trails = HikingTrail::select('id', 'name', 'slug', 'location', 'cover_image', 'difficulty_level', 'elevation', 'distance')
                    ->take(4)
                    ->get();

        // 2. Ambil Open Trip (Yang statusnya Open, urut terbaru, limit 3)
        $trips = OpenTrip::with(['user', 'hikingTrail'])
                    ->where('status', 'open')
                    ->latest()
                    ->take(3)
                    ->get();

        // 3. Ambil Galeri Momen (Limit 6 foto)
        $gallery = CommunityPost::latest()->take(6)->get();

        return response()->json([
            'message' => 'Data Homepage berhasil diambil',
            'data' => [
                'trails' => $trails,
                'trips' => $trips,
                'gallery' => $gallery
            ]
        ]);

        // 4. Ambil Berita
        // Ambil 1 berita penting terbaru
        $importantNews = News::where('is_important', true)->latest()->first();
        
        // Ambil 3 berita biasa terbaru
        $latestNews = News::where('is_important', false)->latest()->take(3)->get();

        return response()->json([
            'message' => 'Data Homepage berhasil diambil',
            'data' => [
                'trails' => $trails,
                'trips' => $trips,
                'gallery' => $gallery,
                // Kirim paket berita
                'news' => [
                    'important' => $importantNews,
                    'list' => $latestNews
                ]
            ]
        ]);
    }
}