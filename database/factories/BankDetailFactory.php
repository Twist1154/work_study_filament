<?php

namespace Database\Factories;

use App\Models\BankDetail;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bank_detail_id' => BankDetail::factory(),
            'student_id' => Student::factory(),
            'account_type' => fake()->regexify('[A-Za-z0-9]{30}'),
            'account_number' => fake()->regexify('[A-Za-z0-9]{30}'),
            'bank_name' => fake()->regexify('[A-Za-z0-9]{100}'),
            'branch_name' => fake()->regexify('[A-Za-z0-9]{100}'),
            'branch_code' => fake()->regexify('[A-Za-z0-9]{20}'),
            'ownership_type' => fake()->regexify('[A-Za-z0-9]{20}'),
            'third_party_name' => fake()->regexify('[A-Za-z0-9]{200}'),
            'third_party_relationship' => fake()->regexify('[A-Za-z0-9]{100}'),
            'valid_from' => fake()->date(),
        ];
    }
}
