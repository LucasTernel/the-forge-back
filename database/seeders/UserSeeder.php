<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = fopen(base_path("database/data/users.csv"), "r");

        fgetcsv($csvFile);

        while (($data = fgetcsv($csvFile)) !== FALSE) {
            $name = $data[0];
            $role = $data[1] ?? 'user';

            $email = Str::slug($name, '.') . "@example.com";

            User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt('ecoal2026'),
                'role' => $role,
                'avatar_url' => "https://api.dicebear.com/7.x/avataaars/svg?seed=" . urlencode($name),
            ]);
        }

        fclose($csvFile);
    }
}