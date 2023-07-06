<?php
/**
 * TicketTest.php
 * php version 8.1.2
 *
 * @category Test
 * @package  Laravel
 * @author   Sergej Sjekloca <segy993@gmail.com>
 * @license  No license
 * @link     https://github.com/Segy93/kanban
 */
namespace Tests\Unit;

use App\Models\Ticket;
use App\Models\User;
use App\Providers\SeedService;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Tests for TicketController methods
 *
 * @category Test
 * @package  Laravel
 * @author   Sergej Sjekloca <segy993@gmail.com>
 * @license  No license
 * @link     https://github.com/Segy93/kanban
 */
class TicketTest extends TestCase
{
    // CREATE

    /**
     * Test if ticket is created successfully
     *
     * @return void
     */
    public function testTicketIsCreatedSuccessfully(): void
    {
        $user = User::inRandomOrder()->first();
        $payload = [
            'title'        => fake()->realText(),
            'description'  => fake()->text(),
            'status'       => array_rand(Ticket::getStatuses()),
            'priority'     => Ticket::max('priority') + 1,
            'user_id'      => $user?->id ?? null,
        ];
        $this->actingAs($user)
            ->json('post', '/api/tickets', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['message',]);
        unset($payload['password']);
        $this->assertDatabaseHas('tickets', $payload);
    }

    /**
     * Tests if ticket create validation failed
     *
     * @return void
     */
    public function testTicketCreateValidationFailed(): void
    {
        $payload = [
            'title'        => Str::random(257),
            'description'  => Str::random(257),
            'status'       => rand(),
            'priority'     => Ticket::max('priority'),
            'user_id'      => 'fail',
        ];
        $this->actingAs(User::inRandomOrder()->first())
            ->json('post', '/api/tickets', $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['message', 'errors']);
    }








    // READ

    /**
     * Test of fetching all tickets successfully
     *
     * @return void
     */
    public function testIndexReturnsDataInValidFormat(): void
    {
        $this->actingAs(User::inRandomOrder()->first())
            ->json('get', '/api/tickets')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
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
     * Test of fetching tickets by status
     *
     * @return void
     */
    public function testLaneReturnsDataInValidFormat(): void
    {
        $status = array_rand(Ticket::getStatuses());
        $this->actingAs(User::inRandomOrder()->first())
            ->json('get', "/api/tickets/status/$status")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
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
     * Test of fetching tickets by status
     *
     * @return void
     */
    public function testLaneStatusNotValid(): void
    {
        $max_allowed = max(array_keys(Ticket::getStatuses()));
        $status = rand($max_allowed + 1, $max_allowed + 10);
        $this->actingAs(User::inRandomOrder()->first())
            ->json('get', "/api/tickets/status/$status")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['message',]);
    }

    /**
     * Test searching of tickets
     *
     * @return void
     */
    public function testSearchTicketReturnsDataInValidFormat(): void
    {
        $ticket = Ticket::inRandomOrder()->first();
        $this->actingAs(User::inRandomOrder()->first())
            ->json('get', "/api/tickets/search/$ticket->title")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
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
    public function testTicketIsShownCorrectly(): void
    {
        $ticket = SeedService::createTicket();

        $user = $ticket->user;

        $this->actingAs($user)
            ->json('get', "/api/tickets/$ticket->id")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(
                [
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
    public function testTicketIsShown404(): void
    {
        $ticket_id = Ticket::max('id') + 1;

        $this->actingAs(User::inRandomOrder()->first())
            ->json('get', "/api/tickets/$ticket_id")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure(['message',]);
    }









    // UPDATE

    /**
     * Test of updating ticket successfully
     *
     * @return void
     */
    public function testUpdateTicketReturnsCorrectData()
    {
        $user = User::inRandomOrder()->first();
        $ticket = SeedService::createTicket();
        $payload = [
            'title'        => fake()->realText(),
            'description'  => fake()->text(),
            'status'       => array_rand(Ticket::getStatuses()),
            'priority_new' => Ticket::inRandomOrder()->first()->priority,
            'priority_old' => $ticket->priority,
            'user_id'      => $user?->id ?? null,
        ];

        $this->actingAs($user)
            ->json('put', "/api/tickets/$ticket->id", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['message',]);
    }

    /**
     * Tests ticket not found update
     *
     * @return void
     */
    public function testTicketUpdate404(): void
    {
        $user = User::inRandomOrder()->first();
        $priority = Ticket::inRandomOrder()->first()->priority;
        $payload = [
            'title'        => fake()->realText(),
            'description'  => fake()->text(),
            'status'       => array_rand(Ticket::getStatuses()),
            'priority_new' => $priority,
            'priority_old' => Ticket::max('priority') + 1,
            'user_id'      => $user?->id ?? null,
        ];
        $ticket_id = Ticket::max('id') + 1;

        $this->actingAs($user)
            ->json('put', "/api/tickets/$ticket_id", $payload)
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure(['message',]);
    }

    /**
     * Tests if ticket update validation failed
     *
     * @return void
     */
    public function testTicketUpdateValidationFailed(): void
    {
        $ticket = SeedService::createTicket();
        $payload = [
            'title'        => Str::random(257),
            'description'  => Str::random(257),
            'status'       => rand(),
            'priority_new' => Ticket::inRandomOrder()->first()->priority,
            'priority_old' => $ticket->priority,
            'user_id'      => 'fail',
        ];

        $this->actingAs(User::inRandomOrder()->first())
            ->json('put', "/api/tickets/$ticket->id", $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['message','errors',]);
    }

    /**
     * Tests if ticket update validation failed
     *
     * @return void
     */
    public function testTicketPriority404Failed(): void
    {
        $user = User::inRandomOrder()->first();
        $ticket = SeedService::createTicket();
        $payload = [
            'title'        => fake()->realText(),
            'description'  => fake()->text(),
            'status'       => array_rand(Ticket::getStatuses()),
            'priority_new' => Ticket::inRandomOrder()->first()->priority,
            'priority_old' => Ticket::max('priority') + 1,
            'user_id'      => $user?->id ?? null,
        ];

        $this->actingAs($user)
            ->json('put', "/api/tickets/$ticket->id", $payload)
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure(['message',]);
    }









    // DELETE

    /**
     * Test of deleting the ticket successfully
     *
     * @return void
     */
    public function testTicketIsDeleted()
    {
        $ticket = SeedService::createTicket();
        $this->actingAs(User::inRandomOrder()->first())
            ->json('delete', "/api/tickets/$ticket->id")
            ->assertStatus(Response::HTTP_NO_CONTENT)
            ->assertNoContent();
        $ticket_array = $ticket->toArray();
        unset($ticket_array['status_name']);
        $this->assertDatabaseMissing('tickets', $ticket_array);
    }

    /**
     * Tests ticket not found delete
     *
     * @return void
     */
    public function testTicketDelete404(): void
    {
        $ticket_id = Ticket::max('id') + 1;

        $this->actingAs(User::inRandomOrder()->first())
            ->json('delete', "/api/tickets/$ticket_id")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure(['message',]);
    }

}
