<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Era;

class EraSeeder extends Seeder
{
    public function run(): void
    {
        $eras = [
            ['name' => 'Préhistoire', 'color' => '#8B4513', 'overlay' => 'dark'],
            ['name' => 'Antiquité', 'color' => '#D4AF37', 'overlay' => 'light'],
            ['name' => 'Moyen Âge', 'color' => '#4A4A4A', 'overlay' => 'dark'],
            ['name' => 'Renaissance', 'color' => '#FF8C00', 'overlay' => 'light'],
            ['name' => 'Époque Moderne', 'color' => '#C0C0C0', 'overlay' => 'light'],
            ['name' => 'Époque Contemporaine', 'color' => '#2F4F4F', 'overlay' => 'dark'],
            ['name' => 'Futur', 'color' => '#00BFFF', 'overlay' => 'light'],
        ];

        foreach ($eras as $index => $era) {
            $era['image_cover'] = 'https://picsum.photos/800/600?random=' . (100 + $index);
            Era::create($era);
        }
    }
}
