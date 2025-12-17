<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun ADMIN
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@muncak.com',
            'password' => Hash::make('admin123'), // Password Admin
            'role' => 'admin',
            'avatar' => 'https://ui-avatars.com/api/?name=Admin&background=0D8ABC&color=fff'
        ]);

        // 2. Buat Akun USER Biasa
        User::create([
            'name' => 'Pendaki Santuy',
            'email' => 'user@muncak.com',
            'password' => Hash::make('user123'), // Password User
            'role' => 'user',
            'avatar' => 'https://ui-avatars.com/api/?name=User&background=random'
        ]);

        // 3. Panggil data dummy Gunung, Berita, dll
        // (Pastikan file ContentSeeder.php Anda masih ada)
        $this->call(ContentSeeder::class);
    }
}