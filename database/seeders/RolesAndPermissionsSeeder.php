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

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear roles si no existen
        $roles = [
            'super-admin' => Permission::all(),
            'admin' => [
                'products.index', 'products.store', 'products.show', 'products.update', 'products.destroy', 'products.showByBarcode',
                'inventory.movements.store', 'sales.store',
            ],
            'cashier' => ['sales.store'],
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
