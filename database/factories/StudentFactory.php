<?php

namespace Database\Factories;

use App\Models\User;
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
            'student_id' => fake()->unique()->randomNumber(5),
            'user_id' => User::factory(),
            'student_number' => 'STU' . fake()->unique()->numberBetween(100000, 999999),
            'surname' => fake()->lastName(),
            'first_names' => fake()->firstName() . ' ' . fake()->firstName(),
            'gender' => fake()->randomElement(['Male', 'Female', 'Other']),
            'date_of_birth' => fake()->date('Y-m-d', '-18 years'),
            'id_passport_number' => fake()->numerify('#############'),
            'sars_tax_number' => fake()->numerify('##########'),
            'is_foreign_student' => false,
            'work_permit_number' => null,
            'work_permit_expiry' => null,
            'fee_account_outstanding' => fake()->boolean(),
            'nsfas_funded' => fake()->boolean(),
            'full_bursary_holder' => fake()->boolean(),
            'bursary_settled_before_sem2' => fake()->boolean(),
        ];
    }
}
