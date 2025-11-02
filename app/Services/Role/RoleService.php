<?php

namespace App\Services\Role;

use App\Repositories\Role\RoleRepository;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;

class RoleService
{
    protected RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Get all roles.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->roleRepository->all();
    }

    /**
     * Get paginated roles.
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginated(int $perPage = 15)
    {
        return $this->roleRepository->paginate($perPage);
    }

    /**
     * Find a role by ID.
     *
     * @param int $id
     * @return Role|null
     */
    public function findById(int $id): ?Role
    {
        return $this->roleRepository->find($id);
    }

    /**
     * Find a role by name.
     *
     * @param string $name
     * @return Role|null
     */
    public function findByName(string $name): ?Role
    {
        return $this->roleRepository->findByName($name);
    }

    /**
     * Create a new role.
     *
     * @param array $data
     * @return Role
     */
    public function create(array $data): Role
    {
        $role = $this->roleRepository->create($data);

        // Assign permissions if provided
        if (isset($data['permission_ids']) && is_array($data['permission_ids'])) {
            $this->roleRepository->syncPermissions($role->id, $data['permission_ids']);
        }

        return $role->load('permissions');
    }

    /**
     * Update a role.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $updated = $this->roleRepository->update($id, $data);

        // Update permissions if provided
        if ($updated && isset($data['permission_ids']) && is_array($data['permission_ids'])) {
            $this->roleRepository->syncPermissions($id, $data['permission_ids']);
        }

        return $updated;
    }

    /**
     * Delete a role.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        // Prevent deletion of system roles
        $role = $this->findById($id);
        if ($role && in_array($role->name, ['admin_system', 'admin_agency', 'user'])) {
            return false;
        }

        return $this->roleRepository->delete($id);
    }

    /**
     * Assign permissions to a role.
     *
     * @param int $roleId
     * @param array $permissionIds
     * @return Role
     */
    public function assignPermissions(int $roleId, array $permissionIds): Role
    {
        return $this->roleRepository->syncPermissions($roleId, $permissionIds);
    }

    /**
     * Get all permissions.
     *
     * @return Collection
     */
    public function getAllPermissions(): Collection
    {
        return $this->roleRepository->getAllPermissions();
    }

    /**
     * Get permissions grouped by module.
     *
     * @return array
     */
    public function getPermissionsGroupedByModule(): array
    {
        return $this->roleRepository->getPermissionsGroupedByModule();
    }

    /**
     * Get users with a specific role.
     *
     * @param int $roleId
     * @return Collection
     */
    public function getUsersByRole(int $roleId): Collection
    {
        return $this->roleRepository->getUsersByRole($roleId);
    }

    /**
     * Assign role to user.
     *
     * @param int $userId
     * @param string $roleName
     * @return bool
     */
    public function assignRoleToUser(int $userId, string $roleName): bool
    {
        $user = \App\Models\User::find($userId);
        if (!$user) {
            return false;
        }

        $user->assignRole($roleName);
        return true;
    }

    /**
     * Remove role from user.
     *
     * @param int $userId
     * @param string $roleName
     * @return bool
     */
    public function removeRoleFromUser(int $userId, string $roleName): bool
    {
        $user = \App\Models\User::find($userId);
        if (!$user) {
            return false;
        }

        $user->removeRole($roleName);
        return true;
    }
}
