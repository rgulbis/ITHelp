<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'class_department' => fake()->randomElement(['Class 301', 'Department Accounting', 'Class 201', 'Department IT']),
            'category' => fake()->randomElement(['Hardware', 'Software', 'Network', 'Other']),
            'priority' => fake()->randomElement(['Low', 'Medium', 'Urgent']),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['Open', 'In Progress', 'Closed']),
            'assigned_to' => \App\Models\User::where('role', 'admin')->inRandomOrder()->first()?->id,
        ];
    }
}
