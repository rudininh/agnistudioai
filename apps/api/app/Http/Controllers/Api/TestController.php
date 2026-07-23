<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class TestController extends Controller
{
    /**
     * Test API connection.
     *
     * @OA\Get(
     *     path="/api/test",
     *     operationId="testApi",
     *     tags={"Test"},
     *     summary="Test API connection",
     *     description="Returns a simple message to verify the API is working",
     *
     *     @OA\Response(
     *         response=200,
     *         description="API is working",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="API is working"),
     *             @OA\Property(property="timestamp", type="string", format="date-time"),
     *             @OA\Property(property="version", type="string", example="1.0.0")
     *         )
     *     )
     * )
     */
    public function hello()
    {
        return response()->json([
            'message' => 'API is working!',
            'timestamp' => now()->toDateTimeString(),
            'version' => '1.0.0',
        ], 200);
    }
}
