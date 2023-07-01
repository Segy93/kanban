<?php

namespace Tests\Unit;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Tests for TicketController methods
 */
class TicketTest extends TestCase
{
    // CREATE

    /**
     * Test if ticket is created successfully
     *
     * @return void
     */
    public function testTicketIsCreatedSuccessfully(): void {
        $faker = \Faker\Factory::create();
        $user = User::inRandomOrder()->first();
        $payload = [
            'title'        => $faker->text,
            'description'  => $faker->text,
            'status'       => rand(0, 2),
            'priority'     => Ticket::max('priority') + 1,
            'user_id'      => $user !== null ? $user->id : null,
        ];
        $this->actingAs(User::inRandomOrder()->first())
            ->json('post', '/tickets', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'message',
            ])
        ;
        unset($payload['password']);
        $this->assertDatabaseHas('tickets', $payload);
    }

    /**
     * Tests if ticket create validation failed
     *
     * @return void
     */
    public function testTicketCreateValidationFailed(): void {
        $faker = \Faker\Factory::create();
        $payload = [
            'title'        => Str::random(257),
            'description'  => Str::random(257),
            'status'       => rand(3, 5),
            'priority'     => Ticket::max('priority'),
            'user_id'      => 'fail',
        ];
        $this->actingAs(User::inRandomOrder()->first())
            ->json('post', '/tickets', $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors',
            ])
        ;
    }








    // READ

    /**
     * Test of fetching all tickets successfully
     *
     * @return void
     */
    public function testIndexReturnsDataInValidFormat(): void {
        $this->actingAs(User::inRandomOrder()->first())
            ->json('get', '/tickets')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'priority',
                    'created_at',
                    'updated_at',
                    'user_id',
                ]
            ]
        );
    }

    /**
     * Test searching of tickets
     *
     * @return void
     */
    public function testSearchTicketReturnsDataInValidFormat(): void {
        $ticket = Ticket::inRandomOrder()->first();
        $this->actingAs(User::inRandomOrder()->first())
            ->json('get', "/tickets/search/$ticket->title")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'priority',
                    'created_at',
                    'updated_at',
                    'user_id',
                ]
            ]
        );
    }

    /**
     * Test fetching single ticket successfully
     *
     * @return void
     */
    public function testTicketIsShownCorrectly(): void {
        $faker = \Faker\Factory::create();
        $user = User::inRandomOrder()->first();
        $ticket = Ticket::create(
            [
                'title'        => $faker->text,
                'description'  => $faker->text,
                'status'       => rand(0, 2),
                'priority'     => Ticket::max('priority') + 1,
                'user_id'      => $user !== null ? $user->id : null,
            ]
        );

        $user = $ticket->user;

        $this->actingAs(User::inRandomOrder()->first())
            ->json('get', "tickets/$ticket->id")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                [
                    'id'                => $ticket->id,
                    'title'             => $ticket->title,
                    'description'       => $ticket->description,
                    'status'            => $ticket->status,
                    'status_name'       => $ticket->status_name,
                    'priority'          => $ticket->priority,
                    'created_at'        => $ticket->created_at,
                    'updated_at'        => $ticket->updated_at,
                    'user_id'           => $ticket->user_id,
                    'user'              => [
                        'id'                => $user->id,
                        'name'              => $user->name,
                        'email'             => $user->email,
                        'email_verified_at' => $user->email_verified_at,
                        'created_at'        => $user->created_at,
                        'updated_at'        => $user->updated_at,
                    ],
                ]
            ]
        );
    }

    /**
     * Tests ticket not found
     *
     * @return void
     */
    public function testTicketIsShown404(): void {
        $ticket_id = Ticket::max('id') + 1;

        $this->actingAs(User::inRandomOrder()->first())
            ->json('get', "tickets/$ticket_id")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                'message',
            ]
        );
    }









    // UPDATE

    /**
     * Test of updating ticket successfully
     *
     * @return void
     */
    public function testUpdateTicketReturnsCorrectData() {
        $faker = \Faker\Factory::create();
        $user = User::inRandomOrder()->first();
        $data = [
            'title'        => $faker->text,
            'description'  => $faker->text,
            'status'       => rand(0, 2),
            'priority'     => Ticket::max('priority') + 1,
            'user_id'      => $user !== null ? $user->id : null,
        ];
        $payload = [
            'title'        => $faker->text,
            'description'  => $faker->text,
            'status'       => rand(0, 2),
            'priority_new' => Ticket::inRandomOrder()->first()->priority,
            'priority_old' => $data['priority'],
            'user_id'      => $user !== null ? $user->id : null,
        ];
        $ticket = Ticket::create(
            $data
        );

        $this->actingAs(User::inRandomOrder()->first())
            ->json('put', "tickets/$ticket->id", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
            ])
        ;
    }

    /**
     * Tests ticket not found update
     *
     * @return void
     */
    public function testTicketUpdate404(): void {
        $faker = \Faker\Factory::create();
        $user = User::inRandomOrder()->first();
        $priority = Ticket::inRandomOrder()->first()->priority;
        $payload = [
            'title'        => $faker->text,
            'description'  => $faker->text,
            'status'       => rand(0, 2),
            'priority_new' => $priority,
            'priority_old' => $priority,
            'user_id'      => $user !== null ? $user->id : null,
        ];
        $ticket_id = Ticket::max('id') + 1;

        $this->actingAs(User::inRandomOrder()->first())
            ->json('put', "tickets/$ticket_id", $payload)
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                'message',
            ])
        ;
    }

    /**
     * Tests if ticket update validation failed
     *
     * @return void
     */
    public function testTicketUpdateValidationFailed(): void {
        $user = User::inRandomOrder()->first();
        $faker = \Faker\Factory::create();
        $data = [
            'title'        => $faker->text,
            'description'  => $faker->text,
            'status'       => rand(0, 2),
            'priority'     => Ticket::max('priority') + 1,
            'user_id'      => $user !== null ? $user->id : null,
        ];
        $payload = [
            'title'        => Str::random(257),
            'description'  => Str::random(257),
            'status'       => rand(3, 5),
            'priority_new' => Ticket::inRandomOrder()->first()->priority,
            'priority_old' => $data['priority'],
            'user_id'      => 'fail',
        ];
        $ticket = Ticket::create(
            $data
        );

        $this->actingAs(User::inRandomOrder()->first())
            ->json('put', "tickets/$ticket->id", $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors',
            ])
        ;
    }

    /**
     * Tests if ticket update validation failed
     *
     * @return void
     */
    public function testTicketPriority404Failed(): void {
        $user = User::inRandomOrder()->first();
        $faker = \Faker\Factory::create();
        $data = [
            'title'        => $faker->text,
            'description'  => $faker->text,
            'status'       => rand(0, 2),
            'priority'     => Ticket::max('priority') + 1,
            'user_id'      => $user !== null ? $user->id : null,
        ];
        $payload = [
            'title'        => Str::random(257),
            'description'  => Str::random(257),
            'status'       => rand(3, 5),
            'priority_new' => Ticket::inRandomOrder()->first()->priority,
            'priority_old' => Ticket::max('priority') + 1,
            'user_id'      => 'fail',
        ];
        $ticket = Ticket::create(
            $data
        );

        $this->actingAs(User::inRandomOrder()->first())
            ->json('put', "tickets/$ticket->id", $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors',
            ])
        ;
    }









    // DELETE

    /**
     * Test of deleting the ticket successfully
     *
     * @return void
     */
    public function testTicketIsDeleted() {
        $faker = \Faker\Factory::create();
        $user = User::inRandomOrder()->first();
        $data = [
            'title'        => $faker->text,
            'description'  => $faker->text,
            'status'       => rand(0, 2),
            'priority'     => Ticket::max('priority') + 1,
            'user_id'      => $user !== null ? $user->id : null,
        ];
        $ticket = Ticket::create(
            $data
        );

        $this->actingAs(User::inRandomOrder()->first())
            ->json('delete', "tickets/$ticket->id")
            ->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('tickets', $data);
    }

    /**
     * Tests ticket not found delete
     *
     * @return void
     */
    public function testTicketDelete404(): void {
        $ticket_id = Ticket::max('id') + 1;

        $this->actingAs(User::inRandomOrder()->first())
            ->json('delete', "tickets/$ticket_id")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                'message',
            ]
        );
    }

}
