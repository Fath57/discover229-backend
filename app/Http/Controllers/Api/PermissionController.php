<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\AssignRoleRequest;
use App\Http\Resources\Role\PermissionResource;
use App\Services\Role\RoleService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class PermissionController extends Controller
{
    use ApiResponse;

    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(): JsonResponse
    {
        $permissions = $this->roleService->getAllPermissions();

        return $this->successResponse(
            PermissionResource::collection($permissions),
            'Permissions retrieved successfully'
        );
    }

   
    public function grouped(): JsonResponse
    {
        $grouped = $this->roleService->getPermissionsGroupedByModule();

        // Transform to include resources
        $transformed = [];
        foreach ($grouped as $module => $permissions) {
            $transformed[$module] = PermissionResource::collection(collect($permissions));
        }

        return $this->successResponse(
            $transformed,
            'Permissions grouped successfully'
        );
    }

    public function assignRoleToUser(AssignRoleRequest $request, int $userId): JsonResponse
    {
        $assigned = $this->roleService->assignRoleToUser($userId, $request->validated()['role_name']);

        if (!$assigned) {
            return $this->notFoundResponse('User not found');
        }

        return $this->successResponse(null, 'Role assigned successfully');
    }

   
    public function removeRoleFromUser(AssignRoleRequest $request, int $userId): JsonResponse
    {
        $removed = $this->roleService->removeRoleFromUser($userId, $request->validated()['role_name']);

        if (!$removed) {
            return $this->notFoundResponse('User not found');
        }

        return $this->successResponse(null, 'Role removed successfully');
    }
}
