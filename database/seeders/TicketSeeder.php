<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 50; $i++) {
            Ticket::create([
                'title'       => $faker->text,
                'description' => $faker->text,
                'status'      => array_rand(Ticket::getStatuses()),
                'priority'    => $i,
            ]);
        }
    }
}
