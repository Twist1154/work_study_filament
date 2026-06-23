<?php

namespace Database\Factories;

use App\Models\Invitation;
use App\Models\Registration;
use App\Models\StaffMember;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'registration_id' => Registration::factory(),
            'invitation_id' => Invitation::factory(),
            'student_id' => Student::factory(),
            'status' => fake()->regexify('[A-Za-z0-9]{30}'),
            'conditions_accepted' => fake()->boolean(),
            'verifier_id' => StaffMember::factory(),
            'hod_approver_id' => StaffMember::factory(),
            'final_approver_id' => StaffMember::factory(),
            'hod_signature_file' => fake()->regexify('[A-Za-z0-9]{500}'),
            'hod_signature_date' => fake()->date(),
            'hod_signature_place' => fake()->regexify('[A-Za-z0-9]{200}'),
            'claims_sheet_pdf_path' => fake()->regexify('[A-Za-z0-9]{500}'),
            'created_at' => fake()->dateTime(),
        ];
    }
}
