<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Country;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

class RegisteredUserController extends Controller
{
    use ApiResponse;


    public function create(): JsonResponse
    {
        return $this->successResponse(Country::query()->get(['id', 'name']), 'Countries retrieved successfully', 501);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function store(RegisterRequest $request): JsonResponse
    {
        try {
            // Create the user
            $user = User::create([
                'firstname' => $request->string('firstname'),
                'lastname' => $request->string('lastname'),
                'email' => $request->string('email'),
                'phone' => $request->string('phone'),
                'country_id' => $request->integer('country_id'),
                'password' => Hash::make($request->string('password')),
            ]);

            // Trigger the Registered event
            event(new Registered($user));

            // Authenticate the user - token is set in HTTP-only cookie
            Auth::guard('web')->login($user);

            // Regenerate session
            $request->session()->regenerate();

            return $this->createdResponse([
                'user' => new UserResource($user),
            ], 'User registered successfully');
        } catch (Throwable $e) {
            Log::error('User registration error', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->errorResponse(
                config('app.debug') ? $e->getMessage() : 'An error occurred during registration. Please try again.',
                500
            );
        }
    }
}
