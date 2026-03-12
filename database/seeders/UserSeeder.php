<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1 Admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'test@example.com',
            'password' => bcrypt('ecoal2026'),
            'role' => 'admin',
            'avatar_url' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Admin',
        ]);

        // 15 Regular users
        User::factory(15)->create([
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);
    }
}
