<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->realText(30),
            'description' => $this->faker->realText(200),
            'starts_at' => $this->faker->dateTimeBetween('now', '+1 year'),
            'location' => $this->faker->address(),
            'status' => $this->faker->randomElement(['pending', 'completed']),
        ];
    }
}
