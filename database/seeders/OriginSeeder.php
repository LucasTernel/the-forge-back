<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Origin;

class OriginSeeder extends Seeder
{
    public function run(): void
    {
        $continents = [
            ['name' => 'Afrique', 'color' => '#FF4500', 'overlay' => 'dark'],
            ['name' => 'Amérique du Nord', 'color' => '#1E90FF', 'overlay' => 'dark'],
            ['name' => 'Amérique du Sud', 'color' => '#32CD32', 'overlay' => 'light'],
            ['name' => 'Antarctique', 'color' => '#00FFFF', 'overlay' => 'light'],
            ['name' => 'Asie', 'color' => '#FFD700', 'overlay' => 'light'],
            ['name' => 'Europe', 'color' => '#8A2BE2', 'overlay' => 'dark'],
            ['name' => 'Océanie', 'color' => '#FF1493', 'overlay' => 'light'],
        ];

        foreach ($continents as $index => $continent) {
            $continent['image_cover'] = 'https://picsum.photos/800/600?random=' . (200 + $index);
            Origin::create($continent);
        }
    }
}
