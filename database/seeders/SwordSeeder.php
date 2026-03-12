<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sword;
use App\Models\Era;
use App\Models\Origin;
use App\Models\Collection;
use App\Models\Criteria;
use Illuminate\Support\Str;

class SwordSeeder extends Seeder
{
    public function run(): void
    {
        $eras = Era::all();
        $origins = Origin::all();
        $collections = Collection::all();
        $allCriterias = Criteria::all();

        if ($collections->isEmpty()) {
            return;
        }

        // Load sword criteria from CSV into a lookup table
        $criteriaData = [];
        if (file_exists(base_path("database/data/sword_criterias.csv"))) {
            $csvCriteria = fopen(base_path("database/data/sword_criterias.csv"), "r");
            $first = true;
            while (($row = fgetcsv($csvCriteria, 2000, ",")) !== FALSE) {
                if ($first) {
                    $first = false;
                    continue;
                }
                if (count($row) < 3)
                    continue; // Skip empty or malformed rows
                $criteriaData[$row[0]][$row[1]] = $row[2];
            }
            fclose($csvCriteria);
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
            if (count($data) < 7)
                continue; // Skip empty or malformed rows

            $name = $data[0];
            $regionRaw = $data[1];
            $eraRaw = $data[2];
            $bladeShape = $data[3];
            $edgeType = $data[4];
            $primaryUse = $data[5];
            $descRaw = $data[6];
            $externalImage = $data[7] ?? null; // Added this line

            $description = "{$descRaw} Shape: {$bladeShape}. Edge: {$edgeType}. Primary use: {$primaryUse}.";
            $shortDescription = $primaryUse;

            // Map Region to Origin
            $origin = $this->mapOrigin($regionRaw, $origins);
            // Map Era
            $era = $this->mapEra($eraRaw, $eras);

            // Assign to a collection (distribute exactly 2 per user/collection)
            $collection = $collections[intdiv($swordCount, 2) % $collections->count()];

            // GESTION IMAGE COVER
            $imageCover = null;
            $localImageName = null; // To store the name of the local file if found

            if ($externalImage && Str::startsWith($externalImage, 'http')) {
                // If an external URL is provided in the CSV, use it directly
                $imageCover = $externalImage;
            }
            else {
                // Otherwise, search for a local image in database/data/swords/
                $slug = Str::slug($name);
                $extensions = ['jpg', 'jpeg', 'png', 'avif'];

                // Get all files in the database/data/swords directory once, safely
                static $allFiles = null;
                if ($allFiles === null) {
                    $path = base_path("database/data/swords");
                    $allFiles = is_dir($path) ? scandir($path) : [];
                }

                foreach ($allFiles as $file) {
                    if ($file === '.' || $file === '..')
                        continue;

                    $fileLower = strtolower($file);
                    // Check exact slug match with extensions
                    foreach ($extensions as $ext) {
                        if ($fileLower === "{$slug}.{$ext}") {
                            $localImageName = $file;
                            break 2;
                        }
                    }

                    // Try partial match (e.g., "viking.jpg" for "Viking Sword")
                    // Or "bastard.jpg" for "Bastard Sword"
                    $nameParts = explode('-', $slug);
                    foreach ($nameParts as $part) {
                        if (strlen($part) < 3)
                            continue; // avoid too short parts like "of"
                        foreach ($extensions as $ext) {
                            if ($fileLower === "{$part}.{$ext}") {
                                $localImageName = $file;
                                break 2;
                            }
                        }
                    }

                    // Handle special case like "wakizashi#.jpg"
                    if (str_contains($fileLower, $slug) && !isset($localImageName)) {
                        foreach ($extensions as $ext) {
                            if (str_ends_with($fileLower, ".{$ext}")) {
                                $localImageName = $file;
                                break 2;
                            }
                        }
                    }
                }
                $imageCover = $localImageName; // Assign the found local image name
            }

            $sword = Sword::create([
                'name' => $name,
                'description' => $description,
                'short_description' => $shortDescription,
                'image_cover' => $imageCover, // Use the determined image cover
                'era_id' => $era->id,
                'origin_id' => $origin->id,
                'collection_id' => $collection->id,
            ]);

            // Physically copy the file to the structured folder: storage/app/public/{collection_id}/{sword_id}/{filename}
            // Only copy if it's a local image that was found
            if ($localImageName) {
                $sourcePath = base_path("database/data/swords/{$localImageName}");
                $destDir = storage_path("app/public/{$collection->id}/{$sword->id}");

                if (!file_exists($destDir)) {
                    mkdir($destDir, 0755, true);
                }

                if (file_exists($sourcePath)) {
                    copy($sourcePath, "{$destDir}/{$localImageName}");
                }
            }

            // Assign criteria from CSV data
            foreach ($allCriterias as $c) {
                $rating = $criteriaData[$name][$c->name] ?? 'N/A';
                $sword->criteria()->attach($c->id, ['rating' => $rating]);
            }

            $swordCount++;
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