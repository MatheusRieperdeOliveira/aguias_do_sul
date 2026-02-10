<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pathfinder;
use Carbon\Carbon;

class PathfinderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pathfinder::factory()->count(20)->create();
    }
}
