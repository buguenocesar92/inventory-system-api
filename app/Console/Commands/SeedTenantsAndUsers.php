<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Models\User;

class SeedTenantsAndUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:tenants-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear tenants de prueba, asignar dominios y generar usuarios para cada tenant.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creando tenants y usuarios...');

        // Crear tenant "foo"
        $tenant1 = Tenant::create(['id' => 'foo']);
        $tenant1->domains()->create(['domain' => 'foo.localhost']);
        $this->info('✅ Tenant foo.localhost creado.');

        // Crear tenant "bar"
        $tenant2 = Tenant::create(['id' => 'bar']);
        $tenant2->domains()->create(['domain' => 'bar.localhost']);
        $this->info('✅ Tenant bar.localhost creado.');

        // Crear usuarios en cada tenant
        Tenant::all()->runForEach(function () {
            User::factory()->create();
        });

        $this->info('✅ Usuarios creados en cada tenant.');
    }
}
