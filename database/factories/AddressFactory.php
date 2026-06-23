<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'address_id' => Address::factory(),
            'student_id' => Student::factory(),
            'street_number' => fake()->numberBetween(-10000, 10000),
            'street_name' => fake()->regexify('[A-Za-z0-9]{150}'),
            'suburb' => fake()->regexify('[A-Za-z0-9]{100}'),
            'city' => fake()->city(),
            'post_code' => fake()->regexify('[A-Za-z0-9]{20}'),
        ];
    }
}
