<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;

/**
 * Base controller for all API controllers.
 * Ensures consistent response formatting.
 */
class ApiBaseController extends Controller
{
    use ApiResponse;
}
