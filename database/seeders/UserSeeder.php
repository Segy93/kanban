<?php

namespace Database\Seeders;

use App\Models\User;
use App\Providers\SeedService;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'      => env('DB_USERNAME'),
            'email'     => fake()->unique()->email,
            'password'  => env('DB_PASSWORD'),
        ]);
        User::factory()
            ->count(SeedService::$count)
            ->create()
        ;
    }
}
