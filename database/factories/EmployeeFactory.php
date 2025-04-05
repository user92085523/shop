<?php

namespace Database\Factories;

use App\Enums\Models\EmployeePosition\Name;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->unique()->numberBetween(1, 10000),
            'position_id' => fake()->numberBetween(1, count(Name::cases())),
            'name' => fake()->name(),
        ];
    }
}
