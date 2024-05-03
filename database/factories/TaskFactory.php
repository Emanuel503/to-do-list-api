<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['comida', 'animales', 'otros', 'tareas', null];

        return [
            'user_id_register'  => fake()->numberBetween(1, 11),
            'id_task_status'    => fake()->numberBetween(1, 3),
            'title'             => fake()->text(100),
            'description'       => fake()->text(),
            'description'       => fake()->text(),
            'category'          => fake()->randomElement($categories),
            'color'             => fake()->numberBetween(100000, 999999)
        ];
    }
}
