<?php

namespace App\Repositories;

use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionRepository
{
    public function getAll()
    {
        return Permission::all();
    }

    public function find(int $permissionId)
    {
        return Permission::findOrFail($permissionId);
    }

    public function create(array $data)
    {
        return Permission::create($data);
    }

    public function update(int $permissionId, array $data)
    {
        $permission = Permission::findOrFail($permissionId);
        $permission->update($data);
        return $permission;
    }

    public function delete(int $permissionId): void
    {
        Permission::findOrFail($permissionId)->delete();
    }

    public function assignPermissionToUser(int $userId, int $permissionId): void
    {
        $user = User::findOrFail($userId);
        $user->givePermissionTo($permissionId); // Usa la función de Spatie para asignar permisos
    }
}
