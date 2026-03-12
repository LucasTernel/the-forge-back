<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Era;
use App\Models\Origin;
use App\Models\Collection;
use App\Models\Sword;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create a Test User
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('ecoal2025'),
            'role' => 'admin',
        ]);

        // 2. Create Historical Eras
        $antiquiteEra = Era::create(['name' => 'Antiquité', 'color' => '#d4af37', 'overlay' => 'light', 'image_cover' => 'https://example.com/antiquite-era.jpg']);
        $moyenAgeEra = Era::create(['name' => 'Moyen Âge', 'color' => '#4a4a4a', 'overlay' => 'dark', 'image_cover' => 'https://example.com/moyen-age-era.jpg']);
        $renaissanceEra = Era::create(['name' => 'Renaissance', 'color' => '#8b4513', 'overlay' => 'light', 'image_cover' => 'https://example.com/renaissance-era.jpg']);
        $epoqueModerneEra = Era::create(['name' => 'Époque Moderne', 'color' => '#c0c0c0', 'overlay' => 'light', 'image_cover' => 'https://example.com/epoque-moderne-era.jpg']);
        $epoqueContemporaineEra = Era::create(['name' => 'Époque Contemporaine', 'color' => '#2f4f4f', 'overlay' => 'dark', 'image_cover' => 'https://example.com/epoque-contemporaine-era.jpg']);

        // 3. Create Origins
        $japan = Origin::create(['name' => 'Japon', 'color' => '#fff', 'overlay' => 'red', 'image_cover' => 'https://example.com/japan-origin.jpg']);
        $europe = Origin::create(['name' => 'Europe', 'color' => '#aaa', 'overlay' => 'blue', 'image_cover' => 'https://example.com/europe-origin.jpg']);

        // 4. Create a Collection for the user
        $collection = Collection::create([
            'name' => 'Ma Collection Légendaire',
            'user_id' => $user->id,
            'image_cover' => 'https://example.com/collection-cover.jpg',
        ]);

        // 5. Create Sword Examples
        Sword::create([
            'name' => 'Masamune',
            'description' => 'Une lame légendaire forgée par le plus grand forgeron du Japon.',
            'short_description' => 'Lame légendaire japonaise.',
            'image_cover' => 'https://example.com/masamune.jpg',
            'era_id' => $moyenAgeEra->id,
            'origin_id' => $japan->id,
            'collection_id' => $collection->id,
        ]);

        Sword::create([
            'name' => 'Excalibur',
            'description' => 'L\'épée mythique du Roi Arthur, censée avoir des pouvoirs magiques.',
            'short_description' => 'Épée mythique européenne.',
            'image_cover' => 'https://example.com/excalibur.jpg',
            'era_id' => $moyenAgeEra->id,
            'origin_id' => $europe->id,
            'collection_id' => $collection->id,
        ]);
    }
}