<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use App\Providers\SeedService;
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
            'title'       => fake()->realText(),
            'description' => fake()->text(),
            'status'      => array_rand(Ticket::getStatuses()),
            'priority'    => fake()->unique()->numberBetween(0, SeedService::$count),
            'user_id'     => User::factory(),
        ];
    }
}
