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
        User::create([
            'name'      => 'test',
            'email'     => 'test@test.com',
            'password'  => 'Test123*',
        ]);
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 50; $i++) {
            User::create([
                'name'      => $faker->name,
                'email'     => $faker->unique()->email,
                'password'  => $faker->password,
            ]);
        }
    }
}
