<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;
use Illuminate\Support\Facades\Hash;

class ProvidersAuthController extends Controller
{
    /**
     * Supported OAuth providers
     */
    private const SUPPORTED_PROVIDERS = ['google', 'facebook', 'github'];

    /**
     * Redirect the user to the OAuth provider authentication page.
     *
     * @param string $provider
     * @return mixed
     */
    public function redirectToProvider(string $provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the OAuth provider callback and authenticate the user.
     *
     * @param string $provider
     * @return mixed
     */
    public function handleProviderCallback(string $provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        try {
            // Get user info from OAuth provider
            $socialiteUser = Socialite::driver($provider)->user();

            // Find or create user
            $user = User::firstOrCreate(
                ['email' => $socialiteUser->getEmail()],
                [
                    'firstname' => $socialiteUser->getName() ?? 'User',
                    'lastname' => '',
                    'password' => Hash::make(Str::random(16)),
                ]
            );


            // Authenticate the user - token is set in HTTP-only cookie
            Auth::guard('web')->login($user);

            // Redirect to frontend
            $frontendUrl = config('app.frontend_url', config('app.url'));
            return redirect($frontendUrl . '/auth/finalize');
        } catch (Throwable $exception) {
            return redirect(config('app.frontend_url') . '/login?error=oauth_failed');
        }
    }

    /**
     * Validate the OAuth provider
     *
     * @param string $provider
     * @return JsonResponse|null
     */
    protected function validateProvider(string $provider): ?JsonResponse
    {
        if (!in_array($provider, self::SUPPORTED_PROVIDERS)) {
            return response()->json(
                ['error' => 'Invalid provider. Supported providers: ' . implode(', ', self::SUPPORTED_PROVIDERS)],
                422
            );
        }

        return null;
    }
}
