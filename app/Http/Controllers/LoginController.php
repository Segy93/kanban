<?php

namespace App\Http\Controllers;

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
            $request->session()->regenerate();

            return response()->json(['message' => 'Successful'], Response::HTTP_OK);
        }

        return response()->json([
            'message' => 'Unauthorized'
        ], Response::HTTP_UNAUTHORIZED);
    }
}