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
     * Data validation for users
     *
     * @param Request $request Http request
     * @param int     $id      User id
     *
     * @return array
     */
    public static function validateData(Request $request, ?int $id = null): array
    {
        $mandatory = $id === null ? 'required' : 'nullable';
        return $request->validate(
            [
                'name' => $mandatory . '|string|max:255',
                'email' => $mandatory . '|string|max:255|email|unique:users,email,'
                    . $id,
                'password' => $mandatory . '|string|max:255',
            ]
        );
    }
}
