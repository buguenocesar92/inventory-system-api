<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Crear permisos relacionados con productos, roles y otros módulos
        $permissions = [
            'products.index',
            'products.store',
            'products.show',
            'products.update',
            'products.destroy',
            'products.showByBarcode',
            'inventory.movements.store',
            'sales.store',
            'roles.with-permissions',               // Permiso para obtener roles con permisos
            'roles.with-permissions.show',          // Permiso para mostrar un rol con permisos
            'roles.update-permissions',             // Permiso para actualizar permisos de roles
            'permission.index',                      // Permiso para obtener todos los permisos
            'users.index',                          // Permiso para obtener todos los usuarios
            'update-roles-users',
            'cash-register.open',
            'cash-register.close',
            'cash-register.status',
            'categories.index',
            'categories.store',
            'categories.show',
            'categories.update',
            'categories.destroy',
            'inventory.movements.index',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear roles y asignar permisos
        $roles = [
            'admin' => Permission::all(),
            'cashier' => ['sales.store', 'products.showByBarcode'],
            'inventory-supervisor' => [
                'products.index', 'products.show', 'products.update', 'inventory.movements.store',
            ],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            if (is_array($permissions)) {
                $role->givePermissionTo($permissions);
            } else {
                $role->syncPermissions($permissions);
            }
        }
    }
}
