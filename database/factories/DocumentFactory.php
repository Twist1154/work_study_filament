<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Document;
use App\Models\Registration;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'document_id' => Document::factory(),
            'student_id' => Student::factory(),
            'registration_id' => Registration::factory(),
            'appointment_id' => Appointment::factory(),
            'document_type' => fake()->regexify('[A-Za-z0-9]{50}'),
            'file_path' => fake()->regexify('[A-Za-z0-9]{500}'),
            'permit_expiry_date' => fake()->date(),
            'uploaded_at' => fake()->dateTime(),
        ];
    }
}
