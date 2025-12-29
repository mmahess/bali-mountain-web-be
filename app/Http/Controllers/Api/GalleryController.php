<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class GalleryController extends Controller
{
    // 1. AMBIL SEMUA FOTO
    public function index()
    {
        $currentUserId = auth()->guard('sanctum')->id();

        $galleries = Gallery::with(['user', 'comments.user'])
            ->withCount('likes')
            // Cek status like user yang sedang login
            ->withExists(['likes as is_liked' => function ($query) use ($currentUserId) {
                $query->where('user_id', $currentUserId);
            }])
            ->latest()
            ->get();

        return response()->json([
            'success' => true, 
            'data' => $galleries
        ]);
    }

    // 2. UPLOAD FOTO BARU
    public function store(Request $request)
    {
        // Validasi: Max 10MB (10240 KB)
        $validator = Validator::make($request->all(), [
            'caption' => 'nullable|string',
            'image'   => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $imageName = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imageName = $file->hashName();
                $file->storeAs('images', $imageName, 'public');
            }

            $gallery = Gallery::create([
                'user_id' => auth()->id(), 
                'caption' => $request->caption ?? '', 
                'image'   => $imageName,
            ]);

            $gallery->load('user');

            return response()->json([
                'success' => true,
                'message' => 'Gambar Berhasil Disimpan!',
                'data'    => $gallery
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // 3. KOMENTAR
    public function storeComment(Request $request, $id)
    {
        $request->validate(['body' => 'required|string']);

        $comment = Comment::create([
            'gallery_id' => $id,
            'user_id'    => auth()->id(),
            'body'       => $request->body
        ]);

        // Return komentar lengkap dengan user-nya
        $newComment = Comment::with('user')->find($comment->id);

        return response()->json(['success' => true, 'data' => $newComment]);
    }

    // 4. LIKE / UNLIKE
    public function toggleLike(Request $request, $id)
    {
        $userId = auth()->id();

        $existingLike = Like::where('gallery_id', $id)
            ->where('user_id', $userId)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $status = 'unliked';
        } else {
            Like::create([
                'gallery_id' => $id,
                'user_id'    => $userId
            ]);
            $status = 'liked';
        }

        $count = Like::where('gallery_id', $id)->count();

        return response()->json([
            'success' => true, 
            'status' => $status, 
            'total_likes' => $count
        ]);
    }
    
    // 5. HAPUS FOTO (UPDATE UNTUK ADMIN)
    public function destroy($id)
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan!'], 404);
        }

        // --- LOGIKA BARU DI SINI ---
        // Izinkan hapus JIKA (User adalah Pemilik) ATAU (User adalah Admin)
        if (auth()->id() !== $gallery->user_id && auth()->user()->role !== 'admin') {
             return response()->json(['message' => 'Unauthorized - Anda tidak punya hak hapus'], 403);
        }

        // Hapus file fisik
        if ($gallery->image) {
            Storage::disk('public')->delete('images/' . $gallery->image);
        }
        
        // Hapus data DB
        $gallery->delete();

        return response()->json(['success' => true, 'message' => 'Data Berhasil Dihapus!'], 200);
    }
}