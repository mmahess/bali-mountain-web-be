<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\HikingTrail;
use App\Models\Review;
use App\Models\HikingTrailImage;
use Illuminate\Support\Facades\Hash;

class ContentSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat Dummy Users (Penulis Review)
        $user1 = User::create([
            'name' => 'Rendi Pratama',
            'email' => 'rendi@example.com',
            'password' => Hash::make('password'),
            'avatar' => 'https://i.pravatar.cc/150?img=3',
            'role' => 'user'
        ]);

        $user2 = User::create([
            'name' => 'Siska Melati',
            'email' => 'siska@example.com',
            'password' => Hash::make('password'),
            'avatar' => 'https://i.pravatar.cc/150?img=9',
            'role' => 'user'
        ]);

        // 2. Ambil Data Gunung
        $abang = HikingTrail::where('slug', 'gunung-abang-suter')->first();
        $batur = HikingTrail::where('slug', 'gunung-batur-toyabungkah')->first();

        // 3. Isi Galeri Foto (HikingTrailImage)
        if ($abang) {
            HikingTrailImage::insert([
                ['hiking_trail_id' => $abang->id, 'image_url' => 'https://images.unsplash.com/photo-1552575354-167823521d95?q=80&w=600'],
                ['hiking_trail_id' => $abang->id, 'image_url' => 'https://images.unsplash.com/photo-1627807212001-4467d0237937?q=80&w=600'],
                ['hiking_trail_id' => $abang->id, 'image_url' => 'https://images.unsplash.com/photo-1594818379496-da1e345b0ded?q=80&w=600'],
            ]);

            // Isi Review Gunung Abang
            Review::create([
                'user_id' => $user1->id,
                'hiking_trail_id' => $abang->id,
                'rating' => 5,
                'comment' => 'Jalurnya sangat asri dan sejuk karena banyak pepohonan. Cocok untuk pemula yang ingin upgrade fisik!'
            ]);

            Review::create([
                'user_id' => $user2->id,
                'hiking_trail_id' => $abang->id,
                'rating' => 4,
                'comment' => 'Pemandangan Danau Batur dari puncak luar biasa. Sayang pas turun agak licin kalau hujan.'
            ]);
        }

        if ($batur) {
            // Isi Review Gunung Batur
            Review::create([
                'user_id' => $user1->id,
                'hiking_trail_id' => $batur->id,
                'rating' => 5,
                'comment' => 'Sunrise terbaik di Bali! Wajib coba telur rebus vulkaniknya.'
            ]);

            // Isi Galeri Batur
            HikingTrailImage::insert([
                 ['hiking_trail_id' => $batur->id, 'image_url' => 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?q=80&w=600'],
                 ['hiking_trail_id' => $batur->id, 'image_url' => 'https://images.unsplash.com/photo-1623832793282-3d4349195b0c?q=80&w=600'],
            ]);
        }
    }
}