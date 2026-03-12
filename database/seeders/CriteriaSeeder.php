<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Criteria;

class CriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $criterias = [
            [
                'name' => 'Longueur',
                'short_description' => 'La longueur totale de l\'arme en cm.',
            ],
            [
                'name' => 'Poids',
                'short_description' => 'Le poids total de l\'arme.',
            ],
            [
                'name' => 'Matériaux',
                'short_description' => 'Les principaux matériaux utilisés (acier, fer, bois, etc.).',
            ],
            [
                'name' => 'Rareté',
                'short_description' => 'Niveau de rareté de l\'arme.',
            ],
            [
                'name' => 'État',
                'short_description' => 'L\'indice de conservation de la pièce.',
            ],
        ];

        foreach ($criterias as $criteria) {
            Criteria::create($criteria);
        }
    }
}
