<?php

namespace Database\Factories;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pathfinder>
 */
class PathfinderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $phone = $this->faker->phoneNumber;
        $phone = str_replace(['(', ')', '-', ' '], '', $phone);
        $ddd = substr($phone, 0, 2);
        $phone_number = substr($phone, 2);

        return [
            'name' => $this->faker->name,
            'status' => 'active',
            'ddd' => $ddd,
            'phone' => $phone_number,
            'full_phone' => $phone,
            'birthday' => $this->faker->date(),
            'age' => $this->faker->numberBetween(10, 15),
            'responsible_name' => $this->faker->name,
            'responsible_phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'address' => $this->faker->address,
            'unit_id' =>  Unit::inRandomOrder()->value('id'),
        ];
    }
}
