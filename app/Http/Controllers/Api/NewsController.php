<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    // --- PUBLIC METHODS ---

    public function index()
    {  
    // Tambahkan with('user')
    $hotNews = News::with('user')->where('is_important', true)->latest()->first();

    $query = News::with('user')->latest(); // Tambahkan with('user')

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
    // Tambahkan with('user')
    $news = News::with('user')->where('slug', $slug)->firstOrFail();
    return response()->json(['data' => $news]);
    }

    // --- ADMIN METHODS (CRUD) ---

    public function store(Request $request)
    {
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'category' => 'required|string',
        'content' => 'required|string',
        'thumbnail' => 'required|string',
        'is_important' => 'boolean'
    ]);

    $validated['slug'] = Str::slug($request->title) . '-' . Str::random(5);
    $validated['excerpt'] = Str::limit(strip_tags($request->input('content')), 150);

    // --- TAMBAHAN PENTING ---
    // Ambil ID user yang sedang login (lewat token)
    $validated['user_id'] = $request->user()->id; 

    $news = News::create($validated);

    return response()->json(['message' => 'Berita berhasil diterbitkan', 'data' => $news]);
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $validated = $request->validate([
            'title' => 'string|max:255',
            'category' => 'string',
            'content' => 'string',
            'thumbnail' => 'string',
            'is_important' => 'boolean'
        ]);

        // Jika judul berubah, update slug juga
        if ($request->has('title')) {
            $validated['slug'] = Str::slug($request->title) . '-' . Str::random(5);
        }

        // Jika konten berubah, update excerpt juga
        if ($request->has('content')) {
            $validated['excerpt'] = Str::limit(strip_tags($request->input('content')), 150);
        }

        $news->update($validated);

        return response()->json([
            'message' => 'Berita berhasil diperbarui',
            'data' => $news
        ]);
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();

        return response()->json([
            'message' => 'Berita berhasil dihapus'
        ]);
    }
}