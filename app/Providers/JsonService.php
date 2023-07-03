<?php


namespace App\Providers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Service for sending json responses
 */
class JsonService {

    /**
     * Sends json response
     *
     * @param mixed   $data      Data or error message
     * @param integer $code      Http code
     *
     * @return JsonResponse
     */
    public static function sendJsonResponse(mixed $data, int $code): JsonResponse {
        return response()->json($data, $code);
    }
}
