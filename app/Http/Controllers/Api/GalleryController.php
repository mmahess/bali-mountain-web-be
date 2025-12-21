<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    // Upload Foto Baru
    public function store(Request $request)
    {
        // 1. VALIDASI: Kita sesuaikan dengan UI
        $validator = Validator::make($request->all(), [
            'caption' => 'nullable|string', // JADI NULLABLE (Boleh kosong)
            'image'   => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', // JADI 5MB (5120 KB)
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors'  => $validator->errors() // Ini yang akan dibaca frontend
            ], 422);
        }

        try {
            // 2. Upload Gambar
            $imageName = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imageName = $file->hashName();
                $file->storeAs('images', $imageName, 'public');
            }

            // 3. Simpan ke Database
            $gallery = Gallery::create([
                'user_id' => auth()->id(), // Ambil ID otomatis dari token login
                'caption' => $request->caption ?? '', // Kalau null, isi string kosong
                'image'   => $imageName,
            ]);

            // Load user-nya sekalian biar frontend gak error pas nampilin nama
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

    // ... (Biarkan fungsi index, storeComment, toggleLike, destroy seperti sebelumnya/kode yang sudah benar)
    
    // Pastikan fungsi index juga sudah benar (mengambil data user & like)
    public function index()
    {
        $currentUserId = auth()->guard('sanctum')->id();

        $galleries = Gallery::with(['user', 'comments.user'])
            ->withCount('likes')
            ->withExists(['likes as is_liked' => function ($query) use ($currentUserId) {
                $query->where('user_id', $currentUserId);
            }])
            ->latest()
            ->get();

        return response()->json(['success' => true, 'data' => $galleries]);
    }
    
    // ... Sisanya tetap sama
}