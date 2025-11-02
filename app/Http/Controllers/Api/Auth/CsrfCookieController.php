<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class CsrfCookieController extends Controller
{
    /**
     * Initialize session and return CSRF token in HTTP-only cookie.
     * This endpoint is handled by Laravel Sanctum automatically.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'message' => 'Session initialized. CSRF token set in cookie.'
        ], 200);
    }
}

