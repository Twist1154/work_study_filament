<?php

namespace Database\Factories;

use App\Models\Campus;
use App\Models\Department;
use App\Models\Invitation;
use App\Models\JobCategory;
use App\Models\StaffMember;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvitationFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'invitation_id' => Invitation::factory(),
            'invitation_token' => fake()->regexify('[A-Za-z0-9]{64}'),
            'job_category_id' => JobCategory::factory(),
            'department_id' => Department::factory(),
            'campus_id' => Campus::factory(),
            'supervisor_id' => StaffMember::factory(),
            'first_names' => fake()->regexify('[A-Za-z0-9]{200}'),
            'surname' => fake()->regexify('[A-Za-z0-9]{100}'),
            'cost_centre' => fake()->regexify('[A-Za-z0-9]{20}'),
            'expires_at' => fake()->dateTime(),
            'status' => fake()->regexify('[A-Za-z0-9]{30}'),
        ];
    }
}
