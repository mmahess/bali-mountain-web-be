<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HikingTrailSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('hiking_trails')->insert([
            [
                'name' => 'Gunung Abang via Desa Suter',
                'slug' => 'gunung-abang-suter',
                'description' => 'Gunung Abang merupakan titik tertinggi ketiga di Bali. Jalur via Desa Suter menawarkan pemandangan kaldera Batur yang memukau. Jalur ini didominasi hutan tropis yang rimbun.',
                'location' => 'Kintamani, Bangli',
                'starting_point' => 'Basecamp Desa Suter',
                'difficulty_level' => 'medium',
                'elevation' => 2151, // mdpl
                'elevation_gain' => 850, // meter
                'distance' => 4.5, // km
                'ticket_price' => 25000.00,
                'is_guide_required' => false,
                // Link Peta 3D kamu
                'map_iframe_url' => 'https://gpx.studio/embed?options=%7B%22token%22%3A%22YOUR_MAPBOX_TOKEN%22%2C%22ids%22%3A%5B%221nuIC3mB6Bq1W64PpKWIus33VJj7r2dP9%22%5D%7D#13.59/-8.2844/115.4183/100.0/42',
                'cover_image' => 'https://images.unsplash.com/photo-1571052679234-9344444585c5?q=80&w=1000&auto=format&fit=crop', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gunung Batur via Toyabungkah',
                'slug' => 'gunung-batur-toyabungkah',
                'description' => 'Gunung Batur adalah gunung berapi aktif paling populer di Bali untuk sunrise trekking.',
                'location' => 'Kintamani, Bangli',
                'starting_point' => 'Parkiran Pura Jati',
                'difficulty_level' => 'easy',
                'elevation' => 1717,
                'elevation_gain' => 700,
                'distance' => 3.5,
                'ticket_price' => 100000.00,
                'is_guide_required' => true, // Wajib guide
                // Contoh dummy iframe lain
                'map_iframe_url' => 'https://gpx.studio/embed?options=...', 
                'cover_image' => 'https://images.unsplash.com/photo-1605606309854-94474776104e?q=80&w=1000',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}