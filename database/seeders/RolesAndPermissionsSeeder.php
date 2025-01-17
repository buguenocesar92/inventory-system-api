<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Crear permisos relacionados con productos
        $permissions = [
            'products.index',
            'products.store',
            'products.show',
            'products.update',
            'products.destroy',
            'products.showByBarcode',
            'inventory.movements.store',
            'sales.store',
        ];

        // Crear permisos relacionados con roles
        $rolePermissions = [
            'role.index',
            'role.store',
            'role.show',
            'role.update',
            'role.destroy',
            'role.assign',
        ];

        // Crear permisos relacionados con permisos
        $permissionPermissions = [
            'permission.index',
            'permission.store',
            'permission.show',
            'permission.update',
            'permission.destroy',
            'permission.assign',
        ];

        $allPermissions = array_merge($permissions, $rolePermissions, $permissionPermissions);

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear roles si no existen
        $roles = [
            'super-admin' => Permission::all(),
            'admin' => array_merge(
                $permissions,
                $rolePermissions,
                $permissionPermissions
            ),
            'cashier' => ['sales.store'],
            'inventory-supervisor' => [
                'products.index',
                'products.show',
                'products.update',
                'inventory.movements.store',
            ],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            if (is_array($permissions)) {
                $role->syncPermissions($permissions);
            } else {
                $role->syncPermissions($permissions);
            }
        }
    }
}
