<?php

namespace App\Repositories\Role;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository
{
    /**
     * Get all roles with permissions.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return Role::with('permissions')->get();
    }

    /**
     * Get paginated roles.
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = 15)
    {
        return Role::with('permissions')->paginate($perPage);
    }

    /**
     * Find a role by ID.
     *
     * @param int $id
     * @return Role|null
     */
    public function find(int $id): ?Role
    {
        return Role::with('permissions')->find($id);
    }

    /**
     * Find a role by name.
     *
     * @param string $name
     * @return Role|null
     */
    public function findByName(string $name): ?Role
    {
        return Role::with('permissions')->where('name', $name)->first();
    }

    /**
     * Create a new role.
     *
     * @param array $data
     * @return Role
     */
    public function create(array $data): Role
    {
        return Role::create([
            'name' => $data['name'],
            'guard_name' => $data['guard_name'] ?? 'web',
        ]);
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
        $role = $this->find($id);
        if (!$role) {
            return false;
        }

        return $role->update(['name' => $data['name']]);
    }

    /**
     * Delete a role.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $role = $this->find($id);
        if (!$role) {
            return false;
        }

        return $role->delete();
    }

    /**
     * Sync permissions to a role.
     *
     * @param int $roleId
     * @param array $permissionIds
     * @return Role
     */
    public function syncPermissions(int $roleId, array $permissionIds): Role
    {
        $role = $this->find($roleId);
        $permissions = Permission::whereIn('id', $permissionIds)->get();
        $role->syncPermissions($permissions);

        return $role->load('permissions');
    }

    /**
     * Get all permissions.
     *
     * @return Collection
     */
    public function getAllPermissions(): Collection
    {
        return Permission::all();
    }

    /**
     * Get permissions grouped by module.
     *
     * @return array
     */
    public function getPermissionsGroupedByModule(): array
    {
        $permissions = Permission::all();
        $grouped = [];

        foreach ($permissions as $permission) {
            $module = explode('.', $permission->name)[0];
            $grouped[$module][] = $permission;
        }

        return $grouped;
    }

    /**
     * Get users with a specific role.
     *
     * @param int $roleId
     * @return Collection
     */
    public function getUsersByRole(int $roleId): Collection
    {
        $role = $this->find($roleId);
        if (!$role) {
            return collect();
        }

        return $role->users;
    }
}
