<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\User\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    use ApiResponse;

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): JsonResponse
    {
        $perPage = request()->get('per_page', 15);
        $users = $this->userService->getPaginated($perPage);

        return $this->paginatedResponse(
            $users->through(fn($user) => new UserResource($user)),
            'Users retrieved successfully'
        );
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->validated());

        return $this->createdResponse(
            new UserResource($user),
            'User created successfully'
        );
    }

    public function show(int $id): JsonResponse
    {
        $user = $this->userService->findById($id);

        if (!$user) {
            return $this->notFoundResponse('User not found');
        }

        return $this->successResponse(
            new UserResource($user),
            'User retrieved successfully'
        );
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = $this->userService->findById($id);

        if (!$user) {
            return $this->notFoundResponse('User not found');
        }

        $this->userService->update($id, $request->validated());
        $updatedUser = $this->userService->findById($id);

        return $this->successResponse(
            new UserResource($updatedUser),
            'User updated successfully'
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $user = $this->userService->findById($id);

        if (!$user) {
            return $this->notFoundResponse('User not found');
        }

        $this->userService->delete($id);

        return $this->successResponse(null, 'User deleted successfully');
    }
}
