<?php

namespace Database\Factories;

use App\Models\Qualification;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class QualificationFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'qualification_id' => Qualification::factory(),
            'student_id' => Student::factory(),
            'qualification_name' => fake()->regexify('[A-Za-z0-9]{200}'),
            'year_obtained' => fake()->numberBetween(-10000, 10000),
        ];
    }
}
