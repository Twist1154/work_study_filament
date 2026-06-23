<?php

namespace Database\Factories;

use App\Models\User;
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
            'staff_id' => fake()->unique()->randomNumber(5),
            'user_id' => User::factory(),
            'staff_number' => 'STF' . fake()->unique()->numberBetween(1000, 9999),
            'full_name' => fake()->name(),
            'role' => fake()->randomElement(['Admin', 'Coordinator', 'Lecturer', 'HR']),
            'department_id' => Department::factory(),
        ];
    }
}
