<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Collection;
use App\Models\User;

class CollectionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $index = 0;

        foreach ($users as $user) {
            Collection::create([
                'name' => 'Collection of ' . $user->name,
                'user_id' => $user->id,
                'image_cover' => 'https://picsum.photos/800/600?random=' . (300 + $index),
            ]);
            $index++;
        }
    }
}