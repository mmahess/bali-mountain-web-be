<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HikingTrail;
use App\Models\User;
use App\Models\OpenTrip;
use App\Models\News;
use App\Models\CommunityPost;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung Total Gunung
        $totalMountains = HikingTrail::count();

        // 2. Hitung User (Kecuali Admin)
        $totalUsers = User::where('role', 'user')->count();

        // 3. Hitung Trip yang Masih Aktif (Status Open)
        $activeTrips = OpenTrip::where('status', 'open')->count();

        // 4. Hitung Artikel Berita
        $totalNews = News::count();

        // 5. Hitung Postingan Komunitas (Galeri)
        $totalPosts = CommunityPost::count();

        return response()->json([
            'message' => 'Statistik dashboard berhasil diambil',
            'data' => [
                'mountains' => $totalMountains,
                'users' => $totalUsers,
                'trips' => $activeTrips,
                'news' => $totalNews,
                'posts' => $totalPosts
            ]
        ]);
    }
}