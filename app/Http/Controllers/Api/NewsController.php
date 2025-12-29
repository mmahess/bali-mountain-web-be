<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; // PENTING: Tambahkan Import Ini

class NewsController extends Controller
{
    // --- PUBLIC METHODS ---

    public function index()
    {  
        // Ambil berita hot (penting)
        $hotNews = News::with('user')->where('is_important', true)->latest()->first();

        $query = News::with('user')->latest();

        // Exclude hot news dari list biasa agar tidak duplikat
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
        // UPDATE: Tambahkan 'comments.user' di sini
        $news = News::with(['user', 'comments.user'])
                    ->where('slug', $slug)
                    ->firstOrFail();
                    
        return response()->json(['data' => $news]);
    }

    // --- ADMIN METHODS (CRUD) ---

    // 1. SIMPAN ARTIKEL (HANDLE UPLOAD)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'required|string',
            // VALIDASI GAMBAR
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'is_important' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($request->title) . '-' . Str::random(5);
        $validated['excerpt'] = Str::limit(strip_tags($request->input('content')), 150);
        $validated['user_id'] = $request->user()->id; 

        // --- PROSES UPLOAD THUMBNAIL ---
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Simpan di storage/app/public/news
            $path = $file->storeAs('news', $filename, 'public');
            $validated['thumbnail'] = $path;
        }

        // Pastikan boolean tersimpan benar (FormData mengirim string "true"/"false")
        if ($request->has('is_important')) {
            $validated['is_important'] = filter_var($request->is_important, FILTER_VALIDATE_BOOLEAN);
        }

        $news = News::create($validated);

        return response()->json(['message' => 'Berita berhasil diterbitkan', 'data' => $news]);
    }

    // 2. UPDATE ARTIKEL (HANDLE GANTI GAMBAR)
    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $validated = $request->validate([
            'title' => 'string|max:255',
            'category' => 'string',
            'content' => 'string',
            // Gambar jadi nullable (User mungkin tidak ganti gambar)
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_important' => 'boolean'
        ]);

        if ($request->has('title')) {
            $validated['slug'] = Str::slug($request->title) . '-' . Str::random(5);
        }

        if ($request->has('content')) {
            $validated['excerpt'] = Str::limit(strip_tags($request->input('content')), 150);
        }

        // --- PROSES GANTI THUMBNAIL ---
        if ($request->hasFile('thumbnail')) {
            // Hapus gambar lama jika ada
            if ($news->thumbnail) {
                Storage::disk('public')->delete($news->thumbnail);
            }

            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('news', $filename, 'public');
            $validated['thumbnail'] = $path;
        }

        if ($request->has('is_important')) {
            $validated['is_important'] = filter_var($request->is_important, FILTER_VALIDATE_BOOLEAN);
        }

        $news->update($validated);

        return response()->json([
            'message' => 'Berita berhasil diperbarui',
            'data' => $news
        ]);
    }

    // 3. HAPUS ARTIKEL
    public function destroy($id)
    {
        $news = News::findOrFail($id);
        
        // Hapus file fisik gambar
        if ($news->thumbnail) {
            Storage::disk('public')->delete($news->thumbnail);
        }
        
        $news->delete();

        return response()->json([
            'message' => 'Berita berhasil dihapus'
        ]);
    }
}