<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'dev',
            'email' => 'dev@aguiasdosul.com',
            'password' => Hash::make('741852'),
        ]);

        User::factory(10)->create();
    }
}
