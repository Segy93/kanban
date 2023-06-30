<?php


namespace App\Providers;

use Illuminate\Http\Request;

class TicketService {

    /**
     * Data validation for ticket creating
     *
     * @param Request $request
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