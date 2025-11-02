<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DisableUserController extends Controller
{
    /**
     * @param User $user
     * @return JsonResponse
     */
    public function update(User $user): JsonResponse
    {
        $user->disabled_at = $user->disabled_at === null ? now() : null;
        $user->save();

        return response()->json([
            'message' => $user->disabled_at === null ? 'User account enabled.' : 'User account disabled.',
            'user' => $user
        ]);
    }
}
