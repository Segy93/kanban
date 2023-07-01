<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
                'name'      => $faker->name,
                'email'     => $faker->unique()->email,
                'password'  => Hash::make($faker->password),
            ]);
        }
    }
}