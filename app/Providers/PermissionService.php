<?php

/**
 * PermissionService.php
 * php version 8.1.2
 *
 * @category Service
 * @package  Laravel
 * @author   Sergej Sjekloca <segy993@gmail.com>
 * @license  No license
 * @link     https://github.com/Segy93/kanban
 */
namespace App\Providers;

use Illuminate\Support\Facades\Auth;

/**
 * Permission service
 *
 * @category Service
 * @package  Laravel
 * @author   Sergej Sjekloca <segy993@gmail.com>
 * @license  No license
 * @link     https://github.com/Segy93/kanban
 */
class PermissionService
{

    /**
     * Checks if session user id is same as user id
     *
     * @param integer $id Id user
     *
     * @return boolean
     */
    public static function checkIfUserIsAllowed(int $id): bool
    {
        if (Auth::id() === $id) {
            return true;
        }
        return false;
    }
}
