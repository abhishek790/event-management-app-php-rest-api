<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->sentence(3),
            'description' => fake()->text,
            'start_time' => fake()->dateTimeBetween('now', '+1 month'),
            'end_time' => fake()->dateTimeBetween('+1 month', '+2 months'),
        ];
    }
}