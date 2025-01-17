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
}
