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
            'campus_id' => fake()->unique()->randomNumber(5),
            'campus_name' => fake()->company() . ' Campus',
            'address' => fake()->address(),
        ];
    }
}
