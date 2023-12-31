<?php

/**
 * TicketService.php
 * php version 8.1.2
 *
 * @category Service
 * @package  Laravel
 * @author   Sergej Sjekloca <segy993@gmail.com>
 * @license  No license
 * @link     https://github.com/Segy93/kanban
 */
namespace App\Providers;

use App\Models\Ticket;
use App\Models\TicketHistory;
use Illuminate\Http\Request;

/**
 * Service for ticket history, and ticket validation
 *
 * @category Service
 * @package  Laravel
 * @author   Sergej Sjekloca <segy993@gmail.com>
 * @license  No license
 * @link     https://github.com/Segy93/kanban
 */
class TicketService
{

    // CREATE

    /**
     * Creates ticket history
     *
     * @param Ticket $ticket Ticket
     *
     * @return void
     */
    public static function createHistory(Ticket $ticket): void
    {
        $history = new TicketHistory();

        $history->title       = $ticket->title;
        $history->description = $ticket->description;
        $history->priority    = $ticket->priority;
        $history->status      = $ticket->status;
        $history->user_id     = $ticket->user_id;
        $history->ticket_id   = $ticket->id;

        $history->save();
    }









    // READ

    /**
     * Gets ticket by priority
     *
     * @param integer $priority Ticket priority
     *
     * @return Ticket|null
     */
    private static function _getTicketByPriority(int $priority): ?Ticket
    {
        $ticket = Ticket::where('priority', $priority);
        return $ticket->first();
    }









    // UPDATE

    /**
     * Ticket priority reorder
     *
     * @param integer $priority_old Old ticket priority (position)
     * @param integer $priority     New ticket priority (position)
     *
     * @return boolean
     */
    public static function reorder(int $priority_old, int $priority): bool
    {
        $return = false;
        $ticket = self::_getTicketByPriority($priority_old);
        if (empty($ticket)) {
            return $return;
        }
        $ticket = Ticket::find($ticket->id);
        if ($ticket->priority !== $priority) {
            $increment  = $ticket->priority > $priority ? 1 : -1;
            $dir        = $ticket->priority > $priority ? 'desc' : 'asc';
            $range      = $ticket->priority > $priority
                ? [$priority, $ticket->priority]
                : [$ticket->priority + 1, $priority]
            ;

            $ticket->priority = 0;
            $ticket->save();

            $tickets = Ticket::whereBetween('priority', $range)
                ->orderBy('priority', $dir)
                ->get();
            foreach ($tickets as $i) {
                $i->priority += $increment;
                $i->save();
            }

            $ticket->priority = $priority;
            $ticket->save();
            $return = true;
        }

        return $return;
    }









    // Validation

    /**
     * Data validation for ticket creating
     *
     * @param Request $request HTTP request
     *
     * @return array
     */
    public static function validateDataCreate(Request $request): array
    {
        return $request->validate(
            [
                'title'        => 'required|string|max:255',
                'description'  => 'required|string|max:255',
                'priority'     => 'required|unique:tickets|integer',
                'status'       => 'required|integer|max:2',
                'user_id'      => 'integer|nullable',
            ]
        );
    }

    /**
     * Data validation for ticket updating
     *
     * @param Request $request Http request
     * @param int     $id      Ticket id
     *
     * @return array
     */
    public static function validateDataUpdate(Request $request, int $id): array
    {
        return $request->validate(
            [
                'title'        => 'string|nullable|max:255',
                'description'  => 'string|nullable|max:255',
                'priority_old' => 'integer|nullable|unique:tickets,priority,' . $id,
                'priority_new' => 'integer|nullable',
                'status'       => 'integer|nullable|max:2',
                'user_id'      => 'integer|nullable',
            ]
        );
    }

    /**
     * Validating status
     *
     * @param int $status Ticket status
     *
     * @return bool
     */
    public static function validateStatus(int $status): bool
    {
        if (array_key_exists($status, Ticket::getStatuses())) {
            return true;
        }
        return false;
    }
}
