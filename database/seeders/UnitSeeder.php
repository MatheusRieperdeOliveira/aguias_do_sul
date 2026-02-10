<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{

    public function run(): void
    {
        $units = [
            [
                'name' => 'Penas',
                'status' => 'active',
            ],
            [
                'name' => 'Beija flor',
                'status' => 'active',
            ],
            [
                'name' => 'Fenix',
                'status' => 'active',
            ],
            [
                'name' => 'Pica pau',
                'status' => 'active',
            ],
            [
                'name' => 'Corujas',
                'status' => 'inactive',
            ],
            [
                'name' => 'Pernilongos',
                'status' => 'inactive',
            ]
        ];

        foreach ($units as $unit)
        {
            Unit::updateOrCreate($unit);
        }
    }
}
