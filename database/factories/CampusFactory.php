<?php

namespace Database\Factories;

use App\Models\Campus;
use Illuminate\Database\Eloquent\Factories\Factory;

class CampusFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'campus_id' => Campus::factory(),
            'campus_name' => fake()->regexify('[A-Za-z0-9]{100}'),
            'address' => fake()->word(),
        ];
    }
}
