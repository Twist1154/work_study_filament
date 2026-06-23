<?php

namespace Database\Factories;

use App\Models\JobCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'job_category_id' => fake()->unique()->randomNumber(5),
            'category_name' => fake()->jobTitle(),
            'max_hours_per_month' => fake()->numberBetween(10, 40),
            'description' => fake()->sentence(),
        ];
    }
}
