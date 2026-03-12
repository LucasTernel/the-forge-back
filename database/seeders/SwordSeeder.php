<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sword;
use App\Models\Era;
use App\Models\Origin;
use App\Models\Collection;

class SwordSeeder extends Seeder
{
    public function run(): void
    {
        $eras = Era::all();
        $origins = Origin::all();
        $collections = Collection::all();

        $swordNames = [
            'Excalibur', 'Masamune', 'Durandal', 'Joyeuse', 'Muramasa',
            'Zulfiqar', 'Honjo Masamune', 'Kusanagi', 'Tizona', 'Colada',
            'Gram', 'Balmung', 'Dyrnwyn', 'Crocea Mors', 'Curtana',
            'Almace', 'Hauteclaire', 'Précieuse', 'Flamberge', 'Zweihänder'
        ];

        foreach ($swordNames as $index => $name) {
            $width = rand(500, 1000);
            $height = rand(500, 1000);
            Sword::create([
                'name' => $name,
                'description' => 'Voici une description détaillée pour cette magnifique épée nommée ' . $name . '. Elle provient de forgerons renommés et possède une histoire fascinante qui a traversé les âges.',
                'short_description' => 'Courte description de l\'épée ' . $name . '.',
                'image_cover' => 'https://picsum.photos/' . $width . '/' . $height . '?random=' . (400 + $index),
                'era_id' => $eras->random()->id,
                'origin_id' => $origins->random()->id,
                'collection_id' => $collections->random()->id,
            ]);
        }
    }
}
