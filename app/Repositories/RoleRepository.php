<?php

namespace App\Repositories;

use Spatie\Permission\Models\Role;

class RoleRepository
{
    public function getAll()
    {
        return Role::all();
    }

    public function find(int $roleId)
    {
        return Role::findOrFail($roleId);
    }

    public function create(array $data)
    {
        return Role::create($data);
    }

    public function update(int $roleId, array $data)
    {
        $role = Role::findOrFail($roleId);
        $role->update($data);
        return $role;
    }

    public function delete(int $roleId): void
    {
        Role::findOrFail($roleId)->delete();
    }

    public function assignToUser(int $userId, int $roleId): void
    {
        $role = Role::findOrFail($roleId);
        $role->users()->attach($userId); // O usar sync() si es necesario
    }

    public function getAllWithPermissions(): \Illuminate\Database\Eloquent\Collection
    {
        return Role::with('permissions')->get();
    }

    public function findWithPermissions(int $roleId): array
    {
        $role = Role::with('permissions')->findOrFail($roleId);
        return [
            'id' => $role->id,
            'name' => $role->name,
            'permissions' => $role->permissions->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                ];
            }),
        ];
    }

    /**
    * Actualizar los permisos de un rol.
    */
    public function updatePermissions(int $roleId, array $permissions): array
    {
        $role = Role::findOrFail($roleId);
        $role->syncPermissions($permissions); // Sincroniza los permisos, eliminando los que no estén en la lista
        return [
            'id' => $role->id,
            'name' => $role->name,
            'permissions' => $role->permissions->map(fn($permission) => [
                'id' => $permission->id,
                'name' => $permission->name,
            ]),
        ];
    }
}
