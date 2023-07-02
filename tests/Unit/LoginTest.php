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
        $user = User::first();
        $payload = [
            'email'     => $user->email,
            'password'  => 'Test123*',
        ];
        $this->json('post', '/login', $payload)
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
        $this->json('post', '/login', $payload)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure([
                'message',
            ])
        ;
    }
}
