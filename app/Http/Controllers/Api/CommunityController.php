<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OpenTrip;
use App\Models\CommunityPost;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function index()
    {
        // 1. Ambil Feed Open Trip (Terbaru)
        $feedTrips = OpenTrip::with(['user', 'hikingTrail'])
                        ->where('status', 'open')
                        ->latest()
                        ->get();

        // 2. Ambil Galeri Momen (Terbaru)
        $galleryPosts = CommunityPost::with('user')
                        ->latest()
                        ->get();

        // 3. (Opsional) Ambil 'My Trips' jika user login (nanti kita handle auth)
        // Untuk sekarang kosong dulu
        $myTrips = [];
        $joinedTrips = [];

        return response()->json([
            'message' => 'Data Komunitas berhasil diambil',
            'data' => [
                'feed' => $feedTrips,
                'gallery' => $galleryPosts,
                'myTrips' => $myTrips,
                'joinedTrips' => $joinedTrips
            ]
        ]);
    }
}