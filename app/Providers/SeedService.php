<?php


namespace App\Providers;

use App\Models\Ticket;
use App\Models\User;

/**
 * Service for database seeding
 */
class SeedService {

    /**
     * Number of rows to seed
     *
     * @var integer
     */
    public static $count = 50;


    // CREATE

    /**
     * Creates user
     *
     * @return User
     */
    public static function createUser(): User {
        $data = [
            'name'     => fake()->name(),
            'email'    => fake()->unique()->safeEmail(),
            'password' => fake()->password(),
        ];

        return User::create($data);
    }

    /**
     * Creates ticket
     *
     * @return Ticket
     */
    public static function createTicket(): Ticket {
        $user = User::inRandomOrder()->first();
        return Ticket::create([
            'title'        => fake()->realText(),
            'description'  => fake()->text(),
            'status'       => array_rand(Ticket::getStatuses()),
            'priority'     => Ticket::max('priority') + 1,
            'user_id'      => $user?->id ?? null,
        ]);
    }
}
