<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    // 1. UPDATE PROFILE (Nama, Avatar)
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|max:5120',
            // Email biasanya tidak boleh diganti sembarangan, jadi kita skip dulu
        ]);

        $data = ['name' => $request->name];

        // Upload Avatar Baru
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada (dan bukan default)
            if ($user->avatar && !str_contains($user->avatar, 'ui-avatars.com')) {
                // Asumsi kamu menyimpan full URL di DB, jadi ambil path relatifnya
                // Sesuaikan logika ini dengan cara kamu menyimpan path gambar
                $oldPath = str_replace(url('/storage/'), '', $user->avatar);
                Storage::disk('public')->delete($oldPath);
            }

            $file = $request->file('avatar');
            $filename = $file->hashName();
            $file->storeAs('avatars', $filename, 'public');
            
            // Simpan Full URL agar frontend mudah
            $data['avatar'] = url("/storage/avatars/$filename");
        }

        $user->update($data);

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user' => $user
        ]);
    }

    // 2. GANTI PASSWORD
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed', // butuh field new_password_confirmation
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Password lama salah'], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json(['message' => 'Password berhasil diubah']);
    }
}