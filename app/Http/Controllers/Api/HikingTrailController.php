<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HikingTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HikingTrailController extends Controller
{
    public function index()
    {
        // Ambil semua data untuk tabel admin
        $trails = HikingTrail::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'message' => 'List data gunung berhasil diambil',
            'data' => $trails
        ]);
    }

    public function show($slug)
    {
        $trail = HikingTrail::with(['images', 'reviews.user'])->where('slug', $slug)->firstOrFail();
        return response()->json(['data' => $trail]);
    }

    // --- TAMBAH (STORE) ---
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string',
            'elevation' => 'required|integer',
            'elevation_gain' => 'required|integer', // BARU
            'difficulty_level' => 'required|in:easy,medium,hard',
            'distance' => 'required|numeric',
            'estimation_time' => 'required|string', // BARU
            'starting_point' => 'required|string',
            'ticket_price' => 'required|numeric',
            'is_guide_required' => 'boolean', // BARU
            'description' => 'required|string',
            'cover_image' => 'required|string',
            'map_iframe_url' => 'nullable|string'
        ]);

        $validated['slug'] = Str::slug($request->name) . '-' . Str::random(5);
        // Hapus baris default elevation_gain = 0 yang lama

        $trail = HikingTrail::create($validated);

        return response()->json(['message' => 'Gunung berhasil ditambahkan', 'data' => $trail]);
    }

    // --- UPDATE ---
    public function update(Request $request, $id)
    {
        $trail = HikingTrail::findOrFail($id);

        $validated = $request->validate([
            'name' => 'string|max:255',
            'location' => 'string',
            'elevation' => 'integer',
            'elevation_gain' => 'integer', // BARU
            'difficulty_level' => 'in:easy,medium,hard',
            'distance' => 'numeric',
            'estimation_time' => 'string', // BARU
            'starting_point' => 'string',
            'ticket_price' => 'numeric',
            'is_guide_required' => 'boolean', // BARU
            'description' => 'string',
            'cover_image' => 'string',
            'map_iframe_url' => 'nullable|string'
        ]);

        if ($request->has('name')) {
            $validated['slug'] = Str::slug($request->name) . '-' . Str::random(5);
        }

        $trail->update($validated);

        return response()->json(['message' => 'Data gunung berhasil diperbarui', 'data' => $trail]);
    }
    
    // --- HAPUS (DESTROY) ---
    public function destroy($id)
    {
        $trail = HikingTrail::findOrFail($id);
        $trail->delete();

        return response()->json([
            'message' => 'Gunung berhasil dihapus'
        ]);
    }
}