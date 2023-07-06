<?php
/**
 * TicketController.php
 * php version 8.1.2
 *
 * @category Controller
 * @package  Laravel
 * @author   Sergej Sjekloca <segy993@gmail.com>
 * @license  No license
 * @link     https://github.com/Segy93/kanban
 */
namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Providers\JsonService;
use App\Providers\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Crud methods for managing tickets
 *
 * @category Controller
 * @package  Laravel
 * @author   Sergej Sjekloca <segy993@gmail.com>
 * @license  No license
 * @link     https://github.com/Segy93/kanban
 */
class TicketController extends Controller
{
    // CREATE

    /**
     * Creating single ticket
     *
     * @param Request $request HTTP request
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $ticket = new Ticket();

        $validated = TicketService::validateDataCreate($request);

        $ticket->title       = $validated['title'];
        $ticket->description = $validated['description'];
        $ticket->priority    = $validated['priority'];
        $ticket->status      = $validated['status'];
        $ticket->user_id     = $validated['user_id'];

        $ticket->save();

        $data = ['message' => 'Ticket created'];
        return JsonService::sendResponse($data, Response::HTTP_CREATED);
    }









    // READ

    /**
     * Fetches all tickets
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $tickets = Ticket::orderBy('priority')->get();

        return JsonService::sendResponse($tickets, Response::HTTP_OK);
    }

    /**
     * Fetches tickets by status
     *
     * @param int $id status
     *
     * @return JsonResponse
     */
    public function lane(int $id): JsonResponse
    {
        if (!TicketService::validateStatus($id)) {
            $data = ['message' => 'Status not valid'];
            $response = Response::HTTP_UNPROCESSABLE_ENTITY;
            return JsonService::sendResponse($data, $response);
        }
        $tickets = Ticket::where('status', $id)
            ->orderBy('priority')
            ->get();

        return JsonService::sendResponse($tickets, Response::HTTP_OK);
    }

    /**
     * Fetch ticket by id
     *
     * @param int $id id of ticket
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $ticket = Ticket::where('id', $id)->with('user')->first();

        if (!empty($ticket)) {
            return JsonService::sendResponse([$ticket], Response::HTTP_OK);
        } else {
            $data = ['message' => 'Ticket not found'];
            return JsonService::sendResponse($data, Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Search tickets
     *
     * @param string $search String used for searching
     *
     * @return JsonResponse
     */
    public function search(string $search): JsonResponse
    {
        $tickets = Ticket::where('title', 'like', '%' . $search . '%')->get();

        return JsonService::sendResponse($tickets, Response::HTTP_OK);
    }









    // UPDATE

    /**
     * Updating of single ticket
     *
     * @param Request $request HTTP request
     * @param int     $id      Ticket id
     *
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
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

            // user_id mandatory parameter since user can be unassigned, then the value is null
            $ticket->user_id = $validated['user_id'];

            $ticket->save();

            if ($reorder === false) {
                $data = ['message' => 'Ticket by priority not found'];
                return JsonService::sendResponse($data, Response::HTTP_NOT_FOUND);
            }

            $data = ['message' => 'Ticket updated'];
            return JsonService::sendResponse($data, Response::HTTP_OK);
        } else {
            $data = ['message' => 'Ticket not found'];
            return JsonService::sendResponse($data, Response::HTTP_NOT_FOUND);
        }
    }









    // DELETE

    /**
     * Deleting single record
     *
     * @param int $id Id of deleting record
     *
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $ticket = Ticket::find($id);

        if (!empty($ticket)) {
            $ticket->delete();

            $data = ['message' => 'Ticket deleted'];
            return JsonService::sendResponse($data, Response::HTTP_NO_CONTENT);
        } else {
            $data = ['message' => 'Ticket not found'];
            return JsonService::sendResponse($data, Response::HTTP_NOT_FOUND);
        }
    }
}
