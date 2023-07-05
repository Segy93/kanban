<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Providers\SeedService;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ticket::factory()
            ->count(SeedService::$count)
            ->create()
        ;
    }
}
