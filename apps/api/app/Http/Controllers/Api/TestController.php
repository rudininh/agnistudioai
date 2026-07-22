
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Test API connection.
     */
    public function hello()
    {
        return response()->json([
            'message' => 'API is working!',
            'timestamp' => now()->toDateTimeString(),
            'version' => '1.0.0'
        ], 200);
    }
}

