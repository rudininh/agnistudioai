<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Return a success response.
     *
     * @param  mixed  $data
     * @return JsonResponse
     */
    protected function apiResponse($data = null, string $message = 'Success', int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => [], // can be extended later
        ], $code);
    }

    /**
     * Return an error response.
     *
     * @return JsonResponse
     */
    protected function apiError(string $message = 'Error', ?array $errors = null, int $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'meta' => [],
            'errors' => $errors,
        ], $code);
    }
}
