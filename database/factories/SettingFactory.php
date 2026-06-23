<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'key' => fake()->regexify('[A-Za-z0-9]{100}'),
            'value' => fake()->regexify('[A-Za-z0-9]{255}'),
            'description' => fake()->text(),
        ];
    }
}
