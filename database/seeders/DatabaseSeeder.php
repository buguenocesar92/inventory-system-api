<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Stancl\Tenancy\Facades\Tenancy;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $seederClass = 'RolesAndPermissionsSeeder';

        // Ejecutar para el landlord
        Artisan::call('db:seed', ['--class' => $seederClass]);

        // Ejecutar para todos los tenants
        Tenancy::all()->each(function ($tenant) use ($seederClass) {
            Tenancy::initialize($tenant);
            Artisan::call('db:seed', ['--class' => $seederClass]);
            Tenancy::end();
        });
    }
}
