<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Providers\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/** CRUD methods for managing tickets */
class TicketController extends Controller
{
    // CREATE

    /**
     * Creating single ticket
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse {
        $ticket = new Ticket();

        $validated = TicketService::validateDataCreate($request);

        $ticket->title       = $validated['title'];
        $ticket->description = $validated['description'];
        $ticket->priority    = $validated['priority'];
        $ticket->status      = $validated['status'];
        $ticket->user_id     = $validated['user_id'];

        $ticket->save();

        return response()->json([
            'message' => 'Ticket created'
        ], Response::HTTP_CREATED);
    }









    // READ

    /**
     * Fetches all tickets
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse {
        $tickets = Ticket::orderBy('priority')->get();

        return response()->json($tickets);
    }

    /**
     * Fetch ticket by id
     *
     * @param int $id    id of ticket
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse {
        $ticket = Ticket::where('id', $id)->with('user')->first();

        if (!empty($ticket)) {
            return response()->json([$ticket]);
        } else {
            return response()->json([
                'message' => 'Ticket not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Search tickets
     *
     * @param string $search    String used for searching
     *
     * @return JsonResponse
     */
    public function search(string $search): JsonResponse {
        $tickets = Ticket::where('title', 'like', '%' . $search . '%');

        return response()->json($tickets);
    }









    // UPDATE

    /**
     * Updating of single ticket
     *
     * @param Request   $request
     * @param int       $id
     *
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse {
        $ticket = Ticket::find($id);

        $validated = TicketService::validateDataUpdate($request, $id);

        if (!empty($ticket)) {
            TicketService::createHistory($ticket);

            if ($validated['title'] !== null) {
                $ticket->title = $validated['title'];
            }
            if ($validated['description'] !== null) {
                $ticket->description = $validated['description'];
            }
            if ($validated['status'] !== null) {
                $ticket->status = $validated['status'];
            }
            if (!is_null($validated['priority_new'])) {
                $reorder = TicketService::reorder($validated['priority_old'], $validated['priority_new']);
            }
            $ticket->user_id = $validated['user_id'];

            $ticket->save();

            if ($reorder === false) {
                return response()->json([
                    'message' => 'Ticket by priority not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'message' => 'Ticket updated'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Ticket not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }









    // DELETE

    /**
     * Deleting single record
     *
     * @param int $id    Id of deleting record
     *
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse {
        $ticket = Ticket::find($id);

        if (!empty($ticket)) {
            $ticket->delete();

            return response()->json([
                'message' => 'Ticket deleted'
            ], Response::HTTP_NO_CONTENT);
        } else {
            return response()->json([
                'message' => 'Ticket not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
