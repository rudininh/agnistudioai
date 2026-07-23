<?php

namespace App\Support;

trait ApiResponse
{
    /**
     * Create a success response.
     *
     * @param  mixed  $data
     */
    public function success($data = null, string $message = 'Success', int $statusCode = 200, array $meta = []): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => $meta,
        ];
    }

    /**
     * Create an error response.
     */
    public function error(string $message = 'Error', int $statusCode = 400, array $errors = [], array $meta = []): array
    {
        return [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'meta' => $meta,
        ];
    }
}
