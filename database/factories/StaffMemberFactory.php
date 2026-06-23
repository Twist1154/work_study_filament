<?php

namespace Database\Factories;

use App\Models\Authentication;
use App\Models\Department;
use App\Models\StaffMember;
use Illuminate\Database\Eloquent\Factories\Factory;

class StaffMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'staff_id' => StaffMember::factory(),
            'authentication_id' => Authentication::factory(),
            'staff_number' => fake()->regexify('[A-Za-z0-9]{50}'),
            'full_name' => fake()->regexify('[A-Za-z0-9]{150}'),
            'role' => fake()->regexify('[A-Za-z0-9]{50}'),
            'department_id' => Department::factory(),
        ];
    }
}
