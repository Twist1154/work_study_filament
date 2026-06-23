<?php

namespace Database\Factories;

use App\Models\StaffMember;
use App\Models\Student;
use App\Models\WorkstudyTerm;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkstudyTermFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'terms_id' => Registration::factory(),
            'student_id' => Student::factory(),
            'supervisor_id' => StaffMember::factory(),
            'student_signature_file' => fake()->regexify('[A-Za-z0-9]{500}'),
            'student_signed_date' => fake()->date(),
            'student_signed_place' => fake()->regexify('[A-Za-z0-9]{200}'),
            'supervisor_signature_file' => fake()->regexify('[A-Za-z0-9]{500}'),
            'supervisor_signed_date' => fake()->date(),
            'supervisor_signed_place' => fake()->regexify('[A-Za-z0-9]{200}'),
            'terms_accepted' => fake()->boolean(),
        ];
    }
}
