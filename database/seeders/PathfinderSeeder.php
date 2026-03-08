<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pathfinder;
use Illuminate\Support\Str;

class PathfinderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'Miguel', 'Arthur', 'Gael', 'Théo', 'Heitor',
            'Ravi', 'Davi', 'Bernardo', 'Noah', 'Gabriel',
            'Helena', 'Alice', 'Laura', 'Maria Alice', 'Sophia',
            'Manuela', 'Maitê', 'Liz', 'Cecília', 'Isabella'
        ];

        foreach ($names as $name) {
            Pathfinder::factory()->create([
                'name' => $name,
                'email' => Str::slug($name) . '@aguiasdosul.com',
            ]);
        }
    }
}
