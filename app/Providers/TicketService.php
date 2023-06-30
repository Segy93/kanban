<?php


namespace App\Providers;

use App\Models\Ticket;
use App\Models\TicketHistory;
use Illuminate\Http\Request;

class TicketService {

    /**
     * Creates ticket history
     *
     * @param Ticket $ticket   Ticket
     *
     * @return void
     */
    public static function createHistory(Ticket $ticket): void {
        $history = new TicketHistory();

        $history->title       = $ticket->title;
        $history->description = $ticket->description;
        $history->priority    = $ticket->priority;
        $history->status      = $ticket->status;
        $history->user_id     = $ticket->user_id;
        $history->ticket_id   = $ticket->id;

        $history->save();
    }

    /**
     * Data validation for ticket creating
     *
     * @param Request $request
     *
     * @return array
     */
    public static function validateDataCreate(Request $request): array {
        return $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string|max:255',
            'priority'     => 'required|unique:tickets|integer',
            'status'       => 'required|integer|max:2',
            'user_id'      => 'integer|nullable',
        ]);
    }

    /**
     * Data validation for ticket updating
     *
     * @param Request $request
     * @param int     $id
     *
     * @return array
     */
    public static function validateDataUpdate(Request $request, int $id): array {
        return $request->validate([
            'title'        => 'string|max:255',
            'description'  => 'string|max:255',
            'priority'     => 'integer|unique:tickets,priority,' . $id,
            'status'       => 'integer|max:2',
            'user_id'      => 'integer|nullable',
        ]);
    }
}