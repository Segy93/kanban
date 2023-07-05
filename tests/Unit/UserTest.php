<?php

namespace Tests\Unit;

use App\Models\User;
use App\Providers\SeedService;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Tests for UserController methods
 */
class UserTest extends TestCase
{
    // CREATE

    /**
     * Tests if user is created successfully
     *
     * @return void
     */
    public function testUserIsCreatedSuccessfully(): void {
        $payload = [
            'name'     => fake()->name(),
            'email'    => fake()->unique()->safeEmail(),
            'password' => fake()->password()
        ];
        $this->actingAs(User::inRandomOrder()->first())
            ->json('post', '/api/users', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'message',
            ])
        ;
        unset($payload['password']);
        $this->assertDatabaseHas('users', $payload);
    }

    /**
     * Tests if user create validation failed
     *
     * @return void
     */
    public function testUserCreateValidationFailed(): void {
        $payload = [
            'name'     => Str::random(500),
            'email'    => Str::random(300),
            'password' => Str::random(200)
        ];
        $this->actingAs(User::inRandomOrder()->first())
            ->json('post', '/api/users', $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors',
            ])
        ;
    }







    // READ

    /**
     * Tests fetching of all users
     *
     * @return void
     */
    public function testIndexReturnsDataInValidFormat(): void {
        $this->actingAs(User::inRandomOrder()->first())
            ->json('get', '/api/users')
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
     * Test searching of users
     *
     * @return void
     */
    public function testSearchUserReturnsDataInValidFormat(): void {
        $user = User::inRandomOrder()->first();
        $this->actingAs($user)
            ->json('get', "/api/users/search/$user->name")
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
        $user = User::create(
            [
                'name'     => fake()->name(),
                'email'    => fake()->unique()->safeEmail(),
                'password' => fake()->password(),
            ]
        );

        $this->actingAs($user)
            ->json('get', "/api/users/$user->id")
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

        $this->actingAs(User::inRandomOrder()->first())
            ->json('get', "/api/users/$user_id")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                'message',
            ]
        );
    }









    // UPDATE

    /**
     * Tests if user is updated successfully
     *
     * @return void
     */
    public function testUpdateUserReturnsCorrectData() {
        $payload = [
            'name'     => fake()->name(),
            'email'    => fake()->unique()->safeEmail(),
            'password' => fake()->password(),
        ];
        $user = SeedService::createUser();

        $this->actingAs($user)
            ->json('put', "/api/users/$user->id", $payload)
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

        $payload = [
            'name'     => fake()->name(),
            'email'    => fake()->unique()->safeEmail(),
            'password' => fake()->password(),
        ];
        $this->actingAs(User::inRandomOrder()->first())
            ->json('put', "/api/users/$user_id", $payload)
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                'message',
            ]
        );
    }

    /**
     * Tests if user update validation failed
     *
     * @return void
     */
    public function testUserUpdateValidationFailed(): void {
        $payload = [
            'name'     => Str::random(500),
            'email'    => Str::random(300),
            'password' => Str::random(200)
        ];
        $user = SeedService::createUser();

        $this->actingAs($user)
            ->json('put', "/api/users/$user->id", $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors',
            ])
        ;
    }

    /**
     * Tests if user update is forbidden
     *
     * @return void
     */
    public function testUserUpdateForbidden(): void {
        $payload = [
            'name'     => fake()->name(),
            'email'    => fake()->unique()->safeEmail(),
            'password' => fake()->password(),
        ];
        $random_user = User::inRandomOrder()->first();
        $user = SeedService::createUser();

        $this->actingAs($random_user)
            ->json('put', "/api/users/$user->id", $payload)
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonStructure([
                'message',
            ])
        ;
    }









    // DELETE

    /**
     * Tests if user is successfully deleted
     *
     * @return void
     */
    public function testUserIsDeleted() {
        $user = SeedService::createUser();

        $this->actingAs($user)
            ->json('delete', "/api/users/$user->id")
            ->assertStatus(Response::HTTP_NO_CONTENT)
            ->assertNoContent()
        ;
        $this->assertDatabaseMissing('users', $user->toArray());
    }

    /**
     * Tests user not found delete
     *
     * @return void
     */
    public function testUserDelete404(): void {
        $user_id = User::max('id') + 1;

        $this->actingAs(User::inRandomOrder()->first())
            ->json('delete', "/api/users/$user_id")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                'message',
            ]
        );
    }

    /**
     * Tests user not found forbidden
     *
     * @return void
     */
    public function testUserDeleteForbidden(): void {
        $random_user = User::inRandomOrder()->first();
        $user = SeedService::createUser();

        $this->actingAs($random_user)
            ->json('delete', "/api/users/$user->id")
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonStructure([
                'message',
            ]
        );
    }
}
