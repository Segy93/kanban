<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Tests for LoginController methods
 */
class LoginTest extends TestCase
{
    // READ

    /**
     * Test if login is successfull
     *
     * @return void
     */
    public function testLoginSuccessfull(): void {
        $user = User::where('name', env('DB_USERNAME'))->first();
        $payload = [
            'email'     => $user->email,
            'password'  => env('DB_PASSWORD'),
        ];
        $this->json('post', '/api/login', $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
            ])
        ;
    }

    /**
     * Test if login failed
     *
     * @return void
     */
    public function testLoginFailed(): void {
        $user = User::first();
        $payload = [
            'email'     => $user->email,
            'password'  => Str::random(),
        ];
        $this->json('post', '/api/login', $payload)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure([
                'message',
            ])
        ;
    }
}
