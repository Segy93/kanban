<?php
/**
 * LoginController.php
 * php version 8.1.2
 *
 * @category Controller
 * @package  Laravel
 * @author   Sergej Sjekloca <segy993@gmail.com>
 * @license  No license
 * @link     https://github.com/Segy93/kanban
 */
namespace App\Http\Controllers;

use App\Providers\JsonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Default laravel authentication
 *
 * @category Controller
 * @package  Laravel
 * @author   Sergej Sjekloca <segy993@gmail.com>
 * @license  No license
 * @link     https://github.com/Segy93/kanban
 */
class LoginController extends Controller
{
    /**
     * Handle authentication attempt
     *
     * @param Request $request HTTP request
     *
     * @return JsonResponse
     */
    public function authenticate(Request $request): JsonResponse
    {
        $credentials = $request->validate(
            [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]
        );

        if (Auth::attempt($credentials)) {
            Auth::user();

            $data = ['message' => 'Successful'];
            return JsonService::sendResponse($data, Response::HTTP_OK);
        }

        $data = ['message' => 'Unauthorized'];
        return JsonService::sendResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}