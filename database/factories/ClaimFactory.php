<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Claim;
use App\Models\StaffMember;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClaimFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'claim_id' => Claim::factory(),
            'appointment_id' => Appointment::factory(),
            'student_id' => Student::factory(),
            'claim_month' => fake()->numberBetween(-10000, 10000),
            'claim_year' => fake()->numberBetween(-10000, 10000),
            'hours_worked' => fake()->randomFloat(2, 0, 999.99),
            'amount_claimed' => fake()->randomFloat(2, 0, 99999999.99),
            'amount_to_fees' => fake()->randomFloat(2, 0, 99999999.99),
            'amount_to_bank' => fake()->randomFloat(2, 0, 99999999.99),
            'approved_by_id' => StaffMember::factory(),
            'status' => fake()->regexify('[A-Za-z0-9]{30}'),
            'is_late_claim' => fake()->boolean(),
            'locked_after_supervisor_approval' => fake()->boolean(),
            'tax_rate_applied' => fake()->randomFloat(4, 0, 9.9999),
        ];
    }
}
