<?php


namespace App\Providers;

use Illuminate\Http\Request;

/**
 * Service for user validation
 */
class UserService {

    /**
     * Data validation for users creating
     *
     * @param Request $request
     *
     * @return array
     */
    public static function validateDataCreate(Request $request): array {
        return $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|max:255|email|unique:users',
            'password'     => 'required|string|max:255',
        ]);
    }

    /**
     * Data validation for users updating
     *
     * @param Request $request
     * @param int     $id
     *
     * @return array
     */
    public static function validateDataUpdate(Request $request, int $id): array {
        return $request->validate([
            'name'         => 'string|max:255|nullable',
            'email'        => 'string|max:255|nullable|email|unique:users,email,' . $id,
            'password'     => 'string|max:255|nullable',
        ]);
    }
}
