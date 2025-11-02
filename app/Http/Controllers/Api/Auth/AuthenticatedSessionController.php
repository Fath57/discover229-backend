<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    use ApiResponse;

    /**
     * Handle an incoming authentication request.
     * 
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function store(LoginRequest $request): JsonResponse
    {
        // Authenticate using Sanctum's built-in SPA authentication
        // The token is automatically stored in an HTTP-only cookie
        $request->authenticate();

        // Regenerate session to prevent session fixation attacks
        $request->session()->regenerate();

        return $this->successResponse([
            'user' => new UserResource($request->user()),
        ], 'Login successful');
    }

    /**
     * Handle an incoming logout request.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        // Logout the user from the web guard
        Auth::guard('web')->logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the CSRF token
        $request->session()->regenerateToken();

        return $this->successResponse(null, 'Logout successful');
    }
}
