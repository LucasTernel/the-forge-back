<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Era;

class EraSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = fopen(base_path("database/data/eras.csv"), "r");
        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Era::create([
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