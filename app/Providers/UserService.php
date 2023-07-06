<?php

/**
 * UserService.php
 * php version 8.1.2
 *
 * @category Service
 * @package  Laravel
 * @author   Sergej Sjekloca <segy993@gmail.com>
 * @license  No license
 * @link     https://github.com/Segy93/kanban
 */
namespace App\Providers;

use Illuminate\Http\Request;

/**
 * Service for user validation
 *
 * @category Service
 * @package  Laravel
 * @author   Sergej Sjekloca <segy993@gmail.com>
 * @license  No license
 * @link     https://github.com/Segy93/kanban
 */
class UserService
{

    /**
     * Data validation for users creating
     *
     * @param Request $request Http request
     *
     * @return array
     */
    public static function validateDataCreate(Request $request): array
    {
        return $request->validate(
            [
                'name'         => 'required|string|max:255',
                'email'        => 'required|string|max:255|email|unique:users',
                'password'     => 'required|string|max:255',
            ]
        );
    }

    /**
     * Data validation for users updating
     *
     * @param Request $request Http request
     * @param int     $id      User id
     *
     * @return array
     */
    public static function validateDataUpdate(Request $request, int $id): array
    {
        return $request->validate(
            [
                'name' => 'string|max:255|nullable',
                'email' => 'string|max:255|nullable|email|unique:users,email,' . $id,
                'password' => 'string|max:255|nullable',
            ]
        );
    }
}
