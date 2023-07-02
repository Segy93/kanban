<?php


namespace App\Providers;

use Illuminate\Support\Facades\Auth;

/**
 * Service for checking permissions
 */
class PermissionService {

    /**
     * Checks if session user id is same as user id
     *
     * @param integer $id
     * @return boolean
     */
    public static function checkIfUserIsAllowed(int $id): bool {
        if (Auth::id() === $id) {
            return true;
        }
        return false;
    }
}
