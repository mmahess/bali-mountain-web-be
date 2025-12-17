<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- 1. ADMIN PERTAMA (Yang sudah ada) ---
        // JANGAN DIHAPUS agar dia tetap ada setelah reset
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@muncak.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'avatar' => 'https://ui-avatars.com/api/?name=Super+Admin&background=0D8ABC&color=fff'
        ]);

        // --- 2. ADMIN KEDUA (BARU KITA TAMBAHKAN) ---
        // Tambahkan ini di bawahnya
        User::create([
            'name' => 'Admin Content',
            'email' => 'content@muncak.com', // Email harus beda
            'password' => Hash::make('content123'),
            'role' => 'admin', // Role tetap admin
            'avatar' => 'https://ui-avatars.com/api/?name=Admin+Content&background=E76F51&color=fff'
        ]);

        // --- 3. USER BIASA (Yang sudah ada) ---
        User::create([
            'name' => 'Pendaki Santuy',
            'email' => 'user@muncak.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'avatar' => 'https://ui-avatars.com/api/?name=Pendaki+Santuy&background=random'
        ]);
        
        // Panggil seeder konten lain (Gunung, Berita, dll)
        $this->call(ContentSeeder::class); 
    }
}