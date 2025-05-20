<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    /**
     * Membuat respons JSON standar untuk seluruh API.
     *
     * @param bool $success
     * @param string $message
     * @param mixed|null $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function jsonResponse(
        bool $success,
        string $message,
        $data = null,
        int $statusCode = 200
    ): JsonResponse {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => $data,
        ], $statusCode);
    }
}
