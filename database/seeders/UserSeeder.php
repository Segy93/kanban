<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 50; $i++) {
            User::create([
                'name'      => $faker->name,
                'email'     => $faker->unique()->email,
                'password'  => Hash::make($faker->password),
            ]);
        }
    }
}
