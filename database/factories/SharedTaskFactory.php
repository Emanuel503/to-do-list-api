<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SharedTask>
 */
class SharedTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_task'   => fake()->numberBetween(1, 40),
            'id_user'   => fake()->numberBetween(1, 11),
        ];
    }
}
