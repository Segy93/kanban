<?php

namespace App\Http\Controllers;

use App\Providers\JsonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Default laravel authentication
 */
class LoginController extends Controller
{
    /**
     * Handle authentication attempt
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function authenticate(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            Auth::user();

            $data = ['message' => 'Successful'];
            return JsonService::sendJsonResponse($data, Response::HTTP_OK);
        }

        $data = ['message' => 'Unauthorized'];
        return JsonService::sendJsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}