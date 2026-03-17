<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Origin;

class OriginSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = fopen(base_path("database/data/origins.csv"), "r");
        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Origin::create([
                    "name" => $data['0'],
                    "image_cover" => $data['1'],
                    "overlay_color" => $data['2']
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}