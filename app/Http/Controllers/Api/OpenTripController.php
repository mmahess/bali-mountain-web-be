<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OpenTrip;
use Illuminate\Http\Request;

class OpenTripController extends Controller
{
    public function index()
    {
        // Tambahkan 'participants' di sini
        $trips = \App\Models\OpenTrip::with(['user', 'hikingTrail', 'participants']) 
                    ->withCount('participants')
                    ->latest()
                    ->get();

        return response()->json(['data' => $trips]);
    }

    // 1. Buat Trip
    public function store(Request $request)
    {
        $validated = $request->validate([
            'hiking_trail_id' => 'required|exists:hiking_trails,id',
            'title' => 'required|string|max:100',
            'meeting_point' => 'required|string',
            'trip_date' => 'required|date|after:today',
            'max_participants' => 'required|integer|min:1',
            'group_chat_link' => 'required|url',
            'description' => 'required|string',
        ]);
        $validated['user_id'] = $request->user()->id;
        $validated['status'] = 'open';
        $trip = OpenTrip::create($validated);
        return response()->json(['message' => 'Sukses', 'data' => $trip]);
    }

    // 2. Ambil Trip Saya
    public function myTrips(Request $request)
    {
        // Load relasi participants agar hitungan jumlah pesertanya akurat
        $trips = OpenTrip::with(['user', 'hikingTrail'])
                    ->withCount('participants') // PENTING: Hitung jumlah peserta
                    ->where('user_id', $request->user()->id)
                    ->latest()
                    ->get();
        return response()->json(['data' => $trips]);
    }

    // 3. Update Trip
    public function update(Request $request, $id)
    {
        $trip = OpenTrip::findOrFail($id);
        if ($trip->user_id !== $request->user()->id) return response()->json(['msg' => 'Unauthorized'], 403);
        $trip->update($request->all());
        return response()->json(['message' => 'Updated', 'data' => $trip]);
    }

    // 4. Hapus Trip
    public function destroy(Request $request, $id)
    {
        $trip = OpenTrip::findOrFail($id);
        if ($trip->user_id !== $request->user()->id) return response()->json(['msg' => 'Unauthorized'], 403);
        $trip->delete();
        return response()->json(['message' => 'Deleted']);
    }

    // 5. GABUNG TRIP (JOIN)
    public function join(Request $request, $id)
    {
        $trip = OpenTrip::findOrFail($id);
        $userId = $request->user()->id;

        // Validasi
        if ($trip->user_id == $userId) {
            return response()->json(['message' => 'Anda leader trip ini.'], 400);
        }
        if ($trip->participants()->where('user_id', $userId)->exists()) {
            return response()->json(['message' => 'Sudah bergabung.'], 400);
        }
        if ($trip->participants()->count() >= $trip->max_participants) {
            return response()->json(['message' => 'Slot penuh.'], 400);
        }

        // Simpan ke Pivot Table
        $trip->participants()->attach($userId);

        return response()->json(['message' => 'Berhasil gabung!']);
    }

    // 6. LIHAT PESERTA (Untuk Modal Kelola)
    public function getParticipants($id)
    {
        // Ambil data trip BESERTA data user pesertanya
        $trip = OpenTrip::with('participants')->findOrFail($id);
        return response()->json(['data' => $trip]);
    }

    public function joinedTrips(Request $request)
    {
        $userId = $request->user()->id;

        // Ambil trip dimana user_id ada di dalam tabel pivot 'participants'
        // TAPI bukan trip buatan sendiri (opsional, biar rapi)
        $trips = \App\Models\OpenTrip::whereHas('participants', function($q) use ($userId) {
                        $q->where('user_id', $userId);
                    })
                    ->where('user_id', '!=', $userId) // Exclude trip sendiri
                    ->with(['user', 'hikingTrail', 'participants']) // Load relasi
                    ->withCount('participants')
                    ->latest()
                    ->get();

        return response()->json(['data' => $trips]);
    }
    public function removeParticipant(Request $request, $tripId, $userId)
    {
        $trip = \App\Models\OpenTrip::findOrFail($tripId);

        // Hanya Leader yang boleh menghapus
        if ($trip->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Hapus dari pivot table
        $trip->participants()->detach($userId);

        return response()->json(['message' => 'Peserta berhasil dihapus']);
    }

    public function leave(Request $request, $id)
    {
        $trip = \App\Models\OpenTrip::findOrFail($id);
        $userId = $request->user()->id;

        // Cek apakah user memang peserta
        if (!$trip->participants()->where('user_id', $userId)->exists()) {
            return response()->json(['message' => 'Anda bukan peserta trip ini'], 400);
        }

        // Leader tidak bisa leave (harus delete trip)
        if ($trip->user_id == $userId) {
            return response()->json(['message' => 'Leader tidak bisa keluar. Hapus trip jika ingin membatalkan.'], 400);
        }

        // Hapus dari pivot
        $trip->participants()->detach($userId);

        return response()->json(['message' => 'Berhasil keluar dari trip']);
    }
}