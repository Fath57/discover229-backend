<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    /**
     * Send reset password email.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        try {
            Log::debug($request->all());
            // Tentative d'envoi du lien de réinitialisation
            $status = Password::sendResetLink($request->only('email'));

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'status' => true,
                    'message' => __($status),
                ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'error' => 'Validation failed',
                'message' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'An error occurred.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reset user's password.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        // Validation des données d'entrée
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            // Tentative de réinitialisation du mot de passe
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill(['password' => Hash::make($password)])->save();
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return response()->json([
                    'status' => true,
                    'message' => __($status),
                ], 200);
            }

            return response()->json([
                'status' => false,
                'error' => 'An error occurred. Please check your informations and try again',
                'message' => __($status),
            ], 500);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'error' => 'Validation failed',
                'message' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'An error occurred. Please check your informations and try again',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
