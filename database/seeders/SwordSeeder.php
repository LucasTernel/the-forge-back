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

        if ($collections->isEmpty()) {
            return;
        }

        $csvFile = fopen(base_path("database/data/swords.csv"), "r");
        $firstline = true;
        $swordCount = 0;
        $collectionIndex = 0;

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if ($firstline) {
                $firstline = false;
                continue;
            }

            $name = $data[0];
            $regionRaw = $data[1];
            $eraRaw = $data[2];
            $bladeShape = $data[3];
            $edgeType = $data[4];
            $primaryUse = $data[5];
            $descRaw = $data[6];

            $description = "{$descRaw} Shape: {$bladeShape}. Edge: {$edgeType}. Primary use: {$primaryUse}.";
            $shortDescription = $primaryUse;

            // Enhanced detection: handle case sensitivity and partial matches
            $slug = \Illuminate\Support\Str::slug($name);
            $extensions = ['jpg', 'jpeg', 'png', 'avif'];
            $imageName = null;

            // Get all files in the directory once (can be optimized by moving outside the loop)
            static $allFiles = null;
            if ($allFiles === null) {
                $allFiles = scandir(storage_path("app/public/swords"));
            }

            foreach ($allFiles as $file) {
                if ($file === '.' || $file === '..') continue;
                
                $fileLower = strtolower($file);
                // Check exact slug match with extensions
                foreach ($extensions as $ext) {
                    if ($fileLower === "{$slug}.{$ext}") {
                        $imageName = $file;
                        break 2;
                    }
                }
                
                // Try partial match (e.g., "viking.jpg" for "Viking Sword")
                // Or "bastard.jpg" for "Bastard Sword"
                $nameParts = explode('-', $slug);
                foreach ($nameParts as $part) {
                    if (strlen($part) < 3) continue; // avoid too short parts like "of"
                    foreach ($extensions as $ext) {
                        if ($fileLower === "{$part}.{$ext}") {
                            $imageName = $file;
                            break 2;
                        }
                    }
                }

                // Handle special case like "wakizashi#.jpg"
                if (str_contains($fileLower, $slug) && !isset($imageName)) {
                     foreach ($extensions as $ext) {
                        if (str_ends_with($fileLower, ".{$ext}")) {
                            $imageName = $file;
                            break 2;
                        }
                    }
                }
            }

            // Map Region to Origin
            $origin = $this->mapOrigin($regionRaw, $origins);
            // Map Era
            $era = $this->mapEra($eraRaw, $eras);

            // Assign to a collection (distribute 2-3 per user)
            $collection = $collections[$collectionIndex % $collections->count()];
            
            $sword = Sword::create([
                'name' => $name,
                'description' => $description,
                'short_description' => $shortDescription,
                'image_cover' => $imageName, // Store raw filename
                'era_id' => $era->id,
                'origin_id' => $origin->id,
                'collection_id' => $collection->id,
            ]);

            // Physically copy the file to the structured folder: storage/app/public/{collection_id}/{sword_id}/{filename}
            if ($imageName) {
                $sourcePath = storage_path("app/public/swords/{$imageName}");
                $destDir = storage_path("app/public/{$collection->id}/{$sword->id}");
                
                if (!file_exists($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                
                copy($sourcePath, "{$destDir}/{$imageName}");
            }

            $swordCount++;
            // Increment collection index every 2-3 swords (simple cycling works too)
            if ($swordCount % 2 == 0 && rand(0, 1) == 1) {
                $collectionIndex++;
            }
        }
        fclose($csvFile);
    }

    private function mapOrigin($region, $origins)
    {
        if (str_contains($region, 'Japan') || str_contains($region, 'China') || str_contains($region, 'India') || str_contains($region, 'Asia')) {
            return $origins->where('name', 'Asia')->first() ?? $origins->random();
        }
        if (str_contains($region, 'Europe')) {
            return $origins->where('name', 'Europe')->first() ?? $origins->random();
        }
        if (str_contains($region, 'Africa')) {
            return $origins->where('name', 'Africa')->first() ?? $origins->random();
        }
        if (str_contains($region, 'Americas')) {
            return $origins->where('name', 'Americas')->first() ?? $origins->random();
        }
        if (str_contains($region, 'Middle East')) {
            return $origins->where('name', 'Asia')->first() ?? $origins->random();
        }
        return $origins->random();
    }

    private function mapEra($eraRaw, $eras)
    {
        if (str_contains($eraRaw, 'Ancient') || str_contains($eraRaw, 'Iron Age') || str_contains($eraRaw, 'BCE') || str_contains($eraRaw, 'Bronze Age') || str_contains($eraRaw, 'Pre-Columbian')) {
            return $eras->where('name', 'Prehistoric & Ancient')->first() ?? $eras->random();
        }
        if (str_contains($eraRaw, '900-1500s') || str_contains($eraRaw, '13th-14th c.') || str_contains($eraRaw, '8th-11th c.') || str_contains($eraRaw, '9th c. CE+')) {
            return $eras->where('name', 'Medieval & Post-Classical')->first() ?? $eras->random();
        }
        if (str_contains($eraRaw, '14th-16th c.') || str_contains($eraRaw, '15th-16th c.')) {
            return $eras->where('name', 'Renaissance & Age of Discovery')->first() ?? $eras->random();
        }
        if (str_contains($eraRaw, '16th-17th c.') || str_contains($eraRaw, '16th-19th c.') || str_contains($eraRaw, '16th c.') || str_contains($eraRaw, '15th-17th c.')) {
            return $eras->where('name', 'Early Modern (16th–17th c.)')->first() ?? $eras->random();
        }
        if (str_contains($eraRaw, '17th-19th c.') || str_contains($eraRaw, '18th c.') || str_contains($eraRaw, '19th c.') || str_contains($eraRaw, '17th-18th c.') || str_contains($eraRaw, '19th-20th c.')) {
            return $eras->where('name', 'Late Modern (18th–19th c.)')->first() ?? $eras->random();
        }
        if (str_contains($eraRaw, 'Ming-Qing') || str_contains($eraRaw, 'Song-Yuan')) {
            return $eras->where('name', 'Regional Dynasties')->first() ?? $eras->random();
        }
        return $eras->random();
    }
}
