<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** CREATE */

    /**
     * Tests if user is created successfully
     *
     * @return void
     */
    public function testUserIsCreatedSuccessfully(): void {
        $faker = \Faker\Factory::create();
        $payload = [
            'name'     => $faker->name,
            'email'    => $faker->unique()->email,
            'password' => $faker->password
        ];
        $this->json('post', '/users', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'message',
            ])
        ;
        unset($payload['password']);
        $this->assertDatabaseHas('users', $payload);
    }









    /** READ */

    /**
     * Tests fetching of all users
     *
     * @return void
     */
    public function testIndexReturnsDataInValidFormat(): void {
        $this->json('get', '/users')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                ]
            ]
        );
    }

    /**
     * Tests fetching of single user
     *
     * @return void
     */
    public function testUserIsShownCorrectly(): void {
        $faker = \Faker\Factory::create();
        $user = User::create(
            [
                'name'     => $faker->name,
                'email'    => $faker->unique()->email,
                'password' => Hash::make($faker->password),
            ]
        );

        $this->json('get', "users/$user->id")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                [
                    'id'                => $user->id,
                    'name'              => $user->name,
                    'email'             => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'created_at'        => $user->created_at,
                    'updated_at'        => $user->updated_at,
                ]
            ]
        );
    }

    /**
     * Tests user not found
     *
     * @return void
     */
    public function testUserIsShown404(): void {
        $user_id = User::max('id') + 1;

        $this->json('get', "users/$user_id")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                'message',
            ]
        );
    }









    /** UPDATE */

    /**
     * Tests if user is updated successfully
     *
     * @return void
     */
    public function testUpdateUserReturnsCorrectData() {
        $faker = \Faker\Factory::create();
        $payload = [
            'name'     => $faker->name,
            'email'    => $faker->unique()->email,
            'password' => Hash::make($faker->password),
        ];
        $payload = [
            'name'     => $faker->name,
            'email'    => $faker->unique()->email,
            'password' => Hash::make($faker->password),
        ];
        $user = User::create(
            $payload
        );

        $this->json('put', "users/$user->id", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
            ])
        ;
    }

    /**
     * Tests user not found update
     *
     * @return void
     */
    public function testUserUpdate404(): void {
        $user_id = User::max('id') + 1;

        $faker = \Faker\Factory::create();
        $payload = [
            'name'     => $faker->name,
            'email'    => $faker->unique()->email,
            'password' => Hash::make($faker->password),
        ];
        $this->json('put', "users/$user_id", $payload)
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                'message',
            ]
        );
    }









    /** DELETE */

    /**
     * Tests if user is successfully deleted
     *
     * @return void
     */
    public function testUserIsDeleted() {
        $faker = \Faker\Factory::create();
        $data = [
            'name'     => $faker->name,
            'email'    => $faker->unique()->email,
            'password' => Hash::make($faker->password),
        ];
        $user = User::create(
            $data
        );

        $this->json('delete', "users/$user->id")
            ->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('users', $data);
    }

    /**
     * Tests user not found delete
     *
     * @return void
     */
    public function testUserDelete404(): void {
        $user_id = User::max('id') + 1;

        $this->json('delete', "users/$user_id")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                'message',
            ]
        );
    }
}
