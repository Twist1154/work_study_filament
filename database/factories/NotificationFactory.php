<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'notification_id' => Notification::factory(),
            'recipient_email' => fake()->regexify('[A-Za-z0-9]{255}'),
            'student_id' => Student::factory(),
            'type' => fake()->regexify('[A-Za-z0-9]{50}'),
            'sent_at' => fake()->dateTime(),
        ];
    }
}
