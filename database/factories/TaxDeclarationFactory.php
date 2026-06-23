<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\TaxDeclaration;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaxDeclarationFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'declaration_id' => TaxDeclaration::factory(),
            'student_id' => Student::factory(),
            'works_less_than_22hrs' => fake()->boolean(),
            'no_other_employer' => fake()->boolean(),
            'declaration_text' => fake()->text(),
            'signed_place' => fake()->regexify('[A-Za-z0-9]{200}'),
            'declaration_date' => fake()->date(),
            'tax_rate_applied' => fake()->randomFloat(4, 0, 9.9999),
        ];
    }
}
