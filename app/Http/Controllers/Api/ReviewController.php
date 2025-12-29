<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\HikingTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    // Simpan Review Baru
    public function store(Request $request, $trailId)
    {
        // 1. Cek apakah gunungnya ada?
        $trail = HikingTrail::find($trailId);
        if (!$trail) return response()->json(['message' => 'Gunung tidak ditemukan'], 404);

        // 2. Validasi Input
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5', // Rating wajib 1-5
            'comment' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 3. Cek apakah user sudah pernah review gunung ini? (Opsional: cegah spam)
        $existingReview = Review::where('user_id', $request->user()->id)
                                ->where('hiking_trail_id', $trailId)
                                ->first();

        if ($existingReview) {
            return response()->json(['message' => 'Anda sudah mereview gunung ini sebelumnya.'], 400);
        }

        // 4. Simpan ke Database
        $review = Review::create([
            'user_id' => $request->user()->id,
            'hiking_trail_id' => $trailId,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        // Load data user biar frontend bisa langsung nampilin nama/avatar
        $review->load('user');

        return response()->json([
            'message' => 'Review berhasil dikirim!',
            'data' => $review
        ], 201);
    }
}