<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        User::create([
            'name'      => env('DB_USERNAME'),
            'email'     => $faker->unique()->email,
            'password'  => env('DB_PASSWORD'),
        ]);
        for ($i = 0; $i < 50; $i++) {
            User::create([
                'name'      => $faker->name,
                'email'     => $faker->unique()->email,
                'password'  => $faker->password,
            ]);
        }
    }
}
