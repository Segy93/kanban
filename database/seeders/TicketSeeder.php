<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 50; $i++) {
            Ticket::create([
                'title'       => Str::random(255),
                'description' => Str::random(255),
                'status'      => rand(0, 2),
                'priority'    => $i,
            ]);
        }
    }
}
