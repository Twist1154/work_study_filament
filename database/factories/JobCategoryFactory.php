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
            'job_category_id' => JobCategory::factory(),
            'category_name' => fake()->regexify('[A-Za-z0-9]{100}'),
            'max_hours_per_month' => fake()->numberBetween(-10000, 10000),
            'description' => fake()->text(),
        ];
    }
}
