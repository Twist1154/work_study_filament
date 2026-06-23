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
            'department_id' => Department::factory(),
            'department_name' => fake()->regexify('[A-Za-z0-9]{100}'),
            'campus_id' => Campus::factory(),
            'contact_details' => fake()->word(),
        ];
    }
}
