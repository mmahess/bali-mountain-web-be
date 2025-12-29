<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HikingTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; // WAJIB: Import Storage

class HikingTrailController extends Controller
{
    public function index()
    {
        $trails = HikingTrail::withAvg('reviews', 'rating')
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        return response()->json(['message' => 'Success', 'data' => $trails]);
    }

    public function show($slug)
    {
        $trail = HikingTrail::with(['images', 'reviews.user'])
                    ->withAvg('reviews', 'rating')
                    ->where('slug', $slug)
                    ->firstOrFail();
        return response()->json(['data' => $trail]);
    }

    // --- TAMBAH DATA (STORE) ---
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string',
            'elevation' => 'required|integer',
            'elevation_gain' => 'required|integer',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'distance' => 'required|numeric',
            'estimation_time' => 'required|string',
            'starting_point' => 'required|string',
            'ticket_price' => 'required|numeric',
            'is_guide_required' => 'boolean',
            'description' => 'required|string',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', 
            'map_iframe_url' => 'nullable|string'
        ]);

        $validated['slug'] = Str::slug($request->name) . '-' . Str::random(5);

        // --- LOGIKA UPLOAD GAMBAR ---
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Simpan di folder: storage/app/public/mountains
            $path = $file->storeAs('mountains', $filename, 'public');
            $validated['cover_image'] = $path;
        }

        // Pastikan boolean benar (karena FormData mengirim string)
        $validated['is_guide_required'] = filter_var($request->is_guide_required, FILTER_VALIDATE_BOOLEAN);

        $trail = HikingTrail::create($validated);

        return response()->json(['message' => 'Gunung berhasil ditambahkan', 'data' => $trail]);
    }

    // --- UPDATE DATA ---
    public function update(Request $request, $id)
    {
        $trail = HikingTrail::findOrFail($id);

        $validated = $request->validate([
            'name' => 'string|max:255',
            'location' => 'string',
            'elevation' => 'integer',
            'elevation_gain' => 'integer',
            'difficulty_level' => 'in:easy,medium,hard',
            'distance' => 'numeric',
            'estimation_time' => 'string',
            'starting_point' => 'string',
            'ticket_price' => 'numeric',
            'is_guide_required' => 'boolean',
            'description' => 'string',
            // Gambar jadi NULLABLE (User tidak wajib ganti foto saat edit)
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'map_iframe_url' => 'nullable|string'
        ]);

        if ($request->has('name')) {
            $validated['slug'] = Str::slug($request->name) . '-' . Str::random(5);
        }

        // --- LOGIKA GANTI GAMBAR ---
        if ($request->hasFile('cover_image')) {
            // Hapus gambar lama dulu agar storage tidak penuh
            if ($trail->cover_image) {
                Storage::disk('public')->delete($trail->cover_image);
            }

            $file = $request->file('cover_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('mountains', $filename, 'public');
            $validated['cover_image'] = $path;
        }

        if ($request->has('is_guide_required')) {
             $validated['is_guide_required'] = filter_var($request->is_guide_required, FILTER_VALIDATE_BOOLEAN);
        }

        $trail->update($validated);

        return response()->json(['message' => 'Data gunung berhasil diperbarui', 'data' => $trail]);
    }
    
    // --- HAPUS DATA ---
    public function destroy($id)
    {
        $trail = HikingTrail::findOrFail($id);
        
        // Hapus file fisik gambar
        if ($trail->cover_image) {
            Storage::disk('public')->delete($trail->cover_image);
        }
        
        $trail->delete();

        return response()->json(['message' => 'Gunung berhasil dihapus']);
    }

    public function popular(Request $request)
    {
        $limit = $request->input('limit', 5);

        $trails = HikingTrail::withAvg('reviews', 'rating')
                    ->orderByDesc('reviews_avg_rating')
                    ->orderByDesc('created_at')
                    ->limit($limit)
                    ->get();

        return response()->json([
            'success' => true,
            'data' => $trails
        ]);
    }
}