<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\AssignPermissionsRequest;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\Role\RoleResource;
use App\Services\Role\RoleService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class RoleController extends Controller
{
    use ApiResponse;

    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(): JsonResponse
    {
        $perPage = request()->get('per_page', 15);
        $roles = $this->roleService->getPaginated($perPage);

        return $this->paginatedResponse(
            $roles->through(fn($role) => new RoleResource($role)),
            'Roles retrieved successfully'
        );
    }

   
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = $this->roleService->create($request->validated());

        return $this->createdResponse(
            new RoleResource($role),
            'Role created successfully'
        );
    }

    public function show(int $id): JsonResponse
    {
        $role = $this->roleService->findById($id);

        if (!$role) {
            return $this->notFoundResponse('Role not found');
        }

        return $this->successResponse(
            new RoleResource($role),
            'Role retrieved successfully'
        );
    }

    public function update(UpdateRoleRequest $request, int $id): JsonResponse
    {
        $role = $this->roleService->findById($id);

        if (!$role) {
            return $this->notFoundResponse('Role not found');
        }

        // Prevent update of system roles
        if (in_array($role->name, ['admin_system', 'admin_agency', 'user'])) {
            return $this->forbiddenResponse('Cannot modify system roles');
        }

        $this->roleService->update($id, $request->validated());
        $updatedRole = $this->roleService->findById($id);

        return $this->successResponse(
            new RoleResource($updatedRole),
            'Role updated successfully'
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $role = $this->roleService->findById($id);

        if (!$role) {
            return $this->notFoundResponse('Role not found');
        }

        $deleted = $this->roleService->delete($id);

        if (!$deleted) {
            return $this->forbiddenResponse('Cannot delete system roles');
        }

        return $this->successResponse(null, 'Role deleted successfully');
    }

    public function assignPermissions(AssignPermissionsRequest $request, int $id): JsonResponse
    {
        $role = $this->roleService->findById($id);

        if (!$role) {
            return $this->notFoundResponse('Role not found');
        }

        $updatedRole = $this->roleService->assignPermissions($id, $request->validated()['permission_ids']);

        return $this->successResponse(
            new RoleResource($updatedRole),
            'Permissions assigned successfully'
        );
    }

  
    public function getUsersByRole(int $id): JsonResponse
    {
        $role = $this->roleService->findById($id);

        if (!$role) {
            return $this->notFoundResponse('Role not found');
        }

        $users = $this->roleService->getUsersByRole($id);

        return $this->successResponse(
            \App\Http\Resources\UserResource::collection($users),
            'Users retrieved successfully'
        );
    }
}
