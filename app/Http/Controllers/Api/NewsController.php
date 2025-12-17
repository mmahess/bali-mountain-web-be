<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        // 1. Ambil 1 Berita Penting (Hot News)
        $hotNews = News::where('is_important', true)->latest()->first();

        // 2. Ambil Berita Lainnya (Kecuali yang sudah jadi Hot News)
        // Jika hotNews ada, kita exclude ID-nya agar tidak dobel
        $query = News::latest();
        
        if ($hotNews) {
            $query->where('id', '!=', $hotNews->id);
        }

        $latestNews = $query->get();

        return response()->json([
            'message' => 'List berita berhasil diambil',
            'data' => [
                'hotNews' => $hotNews,
                'latestNews' => $latestNews
            ]
        ]);
    }

    public function show($slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();
        return response()->json(['data' => $news]);
    }
}