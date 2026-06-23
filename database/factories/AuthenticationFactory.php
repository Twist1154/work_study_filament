<?php

namespace Database\Factories;

use App\Models\Authentication;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthenticationFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'authentication_id' => Authentication::factory(),
            'email_address' => fake()->regexify('[A-Za-z0-9]{255}'),
            'password_hash' => fake()->regexify('[A-Za-z0-9]{255}'),
            'is_locked' => fake()->boolean(),
        ];
    }
}
