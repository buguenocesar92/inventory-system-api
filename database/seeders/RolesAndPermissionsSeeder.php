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
        Permission::create(['name' => 'products.index']);
        Permission::create(['name' => 'products.store']);
        Permission::create(['name' => 'products.show']);
        Permission::create(['name' => 'products.update']);
        Permission::create(['name' => 'products.destroy']);
        Permission::create(['name' => 'products.showByBarcode']);

        // Crear permisos relacionados con inventario
        Permission::create(['name' => 'inventory.movements.store']);

        // Crear permisos relacionados con ventas
        Permission::create(['name' => 'sales.store']);

        // Crear roles
        $superAdmin = Role::create(['name' => 'super-admin']);
       /*  $admin = Role::create(['name' => 'admin']); */
        $cashier = Role::create(['name' => 'cashier']);
        $inventorySupervisor = Role::create(['name' => 'inventory-supervisor']);

        // Asignar permisos a roles
        $superAdmin->givePermissionTo(Permission::all());

/*         $admin->givePermissionTo([
            'products.index', 'products.store', 'products.show', 'products.update', 'products.destroy',
            'inventory.movements.store',
            'sales.store',
        ]); */

        $cashier->givePermissionTo([
            'sales.store',
        ]);

        $inventorySupervisor->givePermissionTo([
            'products.index', 'products.show', 'products.update',
            'inventory.movements.store',
        ]);

    }
}
