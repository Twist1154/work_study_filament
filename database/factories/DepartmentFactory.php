<?php

namespace Database\Factories;

use App\Models\Campus;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'department_id' => fake()->unique()->randomNumber(5),
            'department_name' => fake()->jobTitle() . ' Department',
            'campus_id' => Campus::factory(),
            'contact_details' => fake()->phoneNumber(),
        ];
    }
}
