<?php

namespace Tests\Unit;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class TicketTest extends TestCase
{
    /** CREATE */

    /**
     * Test if ticket is created successfully
     *
     * @return void
     */
    public function testTicketIsCreatedSuccessfully(): void {
        $faker = \Faker\Factory::create();
        $payload = [
            'title'        => $faker->title,
            'description'  => $faker->text,
            'status'       => rand(0, 2),
            'priority'     => Ticket::max('priority') + 1,
            'user_id'      => User::inRandomOrder()->first()->id,
        ];
        $this->json('post', '/tickets', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'message',
            ])
        ;
        unset($payload['password']);
        $this->assertDatabaseHas('tickets', $payload);
    }









    /** READ */

    /**
     * Test of fetching all tickets successfully
     *
     * @return void
     */
    public function testIndexReturnsDataInValidFormat(): void {
        $this->json('get', '/tickets')
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
    public function testSearchReturnsDataInValidFormat(): void {
        $ticket = Ticket::inRandomOrder()->first();
        $this->json('get', "/tickets/search/$ticket->title")
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
        $ticket = Ticket::create(
            [
                'title'        => $faker->title,
                'description'  => $faker->text,
                'status'       => rand(0, 2),
                'priority'     => Ticket::max('priority') + 1,
                'user_id'      => User::inRandomOrder()->first()->id,
            ]
        );

        $this->json('get', "tickets/$ticket->id")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                [
                    'id'                => $ticket->id,
                    'title'             => $ticket->title,
                    'description'       => $ticket->description,
                    'status'            => $ticket->status,
                    'status_text'       => $ticket->status_text,
                    'priority'          => $ticket->priority,
                    'created_at'        => $ticket->created_at,
                    'updated_at'        => $ticket->updated_at,
                    'user_id'           => $ticket->user_id,
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

        $this->json('get', "tickets/$ticket_id")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                'message',
            ]
        );
    }









    /** UPDATE */

    /**
     * Test of updating ticket successfully
     *
     * @return void
     */
    public function testUpdateTicketReturnsCorrectData() {
        $faker = \Faker\Factory::create();
        $payload = [
            'title'        => $faker->title,
            'description'  => $faker->text,
            'status'       => rand(0, 2),
            'priority'     => Ticket::max('priority') + 1,
            'user_id'      => User::inRandomOrder()->first()->id,
        ];
        $ticket = Ticket::create(
            $payload
        );

        $this->json('put', "tickets/$ticket->id", $payload)
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
        $payload = [
            'title'        => $faker->title,
            'description'  => $faker->text,
            'status'       => rand(0, 2),
            'priority'     => Ticket::max('priority') + 1,
            'user_id'      => User::inRandomOrder()->first()->id,
        ];
        $ticket_id = Ticket::max('id') + 1;

        $this->json('put', "tickets/$ticket_id", $payload)
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                'message',
            ])
        ;
    }









    /** DELETE */

    /**
     * Test of deleting the ticket successfully
     *
     * @return void
     */
    public function testTicketIsDeleted() {
        $faker = \Faker\Factory::create();
        $data = [
            'title'        => $faker->title,
            'description'  => $faker->text,
            'status'       => rand(0, 2),
            'priority'     => Ticket::max('priority') + 1,
            'user_id'      => User::inRandomOrder()->first()->id,
        ];
        $ticket = Ticket::create(
            $data
        );

        $this->json('delete', "tickets/$ticket->id")
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

        $this->json('delete', "tickets/$ticket_id")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                'message',
            ]
        );
    }

}
