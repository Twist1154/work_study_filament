<?php

namespace Database\Factories;

use App\Models\Authentication;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'authentication_id' => Authentication::factory(),
            'student_number' => fake()->regexify('[A-Za-z0-9]{50}'),
            'surname' => fake()->regexify('[A-Za-z0-9]{100}'),
            'first_names' => fake()->regexify('[A-Za-z0-9]{200}'),
            'gender' => fake()->regexify('[A-Za-z0-9]{20}'),
            'date_of_birth' => fake()->date(),
            'id_passport_number' => fake()->regexify('[A-Za-z0-9]{50}'),
            'sars_tax_number' => fake()->regexify('[A-Za-z0-9]{20}'),
            'is_foreign_student' => fake()->boolean(),
            'work_permit_number' => fake()->regexify('[A-Za-z0-9]{50}'),
            'work_permit_expiry' => fake()->date(),
            'fee_account_outstanding' => fake()->boolean(),
            'nsfas_funded' => fake()->boolean(),
            'full_bursary_holder' => fake()->boolean(),
            'bursary_settled_before_sem2' => fake()->boolean(),
        ];
    }
}
