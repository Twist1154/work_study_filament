<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Campus;
use App\Models\Department;
use App\Models\JobCategory;
use App\Models\Registration;
use App\Models\StaffMember;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'appointment_id' => Appointment::factory(),
            'student_id' => Student::factory(),
            'job_category_id' => JobCategory::factory(),
            'department_id' => Department::factory(),
            'campus_id' => Campus::factory(),
            'supervisor_id' => StaffMember::factory(),
            'registration_id' => Registration::factory(),
            'commencement_date' => fake()->date(),
            'termination_date' => fake()->date(),
            'remuneration_rate_per_hour' => fake()->randomFloat(2, 0, 99999999.99),
            'cost_centre' => fake()->regexify('[A-Za-z0-9]{20}'),
            'appointment_type' => fake()->regexify('[A-Za-z0-9]{30}'),
            'status' => fake()->regexify('[A-Za-z0-9]{30}'),
        ];
    }
}
