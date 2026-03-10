<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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

        // 2. Create Types
        $katanaType = \App\Models\Type::create(['name' => 'Katana']);
        $longswordType = \App\Models\Type::create(['name' => 'Épée longue']);
        $daggerType = \App\Models\Type::create(['name' => 'Dagger']);
        $rapierType = \App\Models\Type::create(['name' => 'Rapier']);
        $halberdType = \App\Models\Type::create(['name' => 'Halberd']);
        $naginataType = \App\Models\Type::create(['name' => 'Naginata']);
        $spearType = \App\Models\Type::create(['name' => 'Spear']);
        $maceType = \App\Models\Type::create(['name' => 'Mace']);
        $warhammerType = \App\Models\Type::create(['name' => 'War Hammer']);
        $flailType = \App\Models\Type::create(['name' => 'Flail']);
        $longbowType = \App\Models\Type::create(['name' => 'Longbow']);
        $crossbowType = \App\Models\Type::create(['name' => 'Crossbow']);
        $claymoreType = \App\Models\Type::create(['name' => 'Claymore']);
        $scimitarType = \App\Models\Type::create(['name' => 'Scimitar']);
        $battleaxeType = \App\Models\Type::create(['name' => 'Battle Axe']);
        $shortswordType = \App\Models\Type::create(['name' => 'Shortsword']);
        $gladiusType = \App\Models\Type::create(['name' => 'Gladius']);
        $morningstarType = \App\Models\Type::create(['name' => 'Morning Star']);

        // 3. Create Origins
        $japan = \App\Models\Origin::create(['name' => 'Japon']);
        $europe = \App\Models\Origin::create(['name' => 'Europe']);

        // 4. Create a Collection for the user
        $collection = \App\Models\Collection::create([
            'name' => 'Ma Collection Légendaire',
            'user_id' => $user->id,
            'image_cover' => 'https://example.com/collection-cover.jpg',
        ]);

        // 5. Create Sword Examples
        \App\Models\Sword::create([
            'name' => 'Masamune',
            'description' => 'Une lame légendaire forgée par le plus grand forgeron du Japon.',
            'short_description' => 'Lame légendaire japonaise.',
            'image_cover' => 'https://example.com/masamune.jpg',
            'type_id' => $katanaType->id,
            'origin_id' => $japan->id,
            'collection_id' => $collection->id,
        ]);

        \App\Models\Sword::create([
            'name' => 'Excalibur',
            'description' => 'L\'épée mythique du Roi Arthur, censée avoir des pouvoirs magiques.',
            'short_description' => 'Épée mythique européenne.',
            'image_cover' => 'https://example.com/excalibur.jpg',
            'type_id' => $longswordType->id,
            'origin_id' => $europe->id,
            'collection_id' => $collection->id,
        ]);
    }
}