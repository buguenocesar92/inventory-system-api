<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tenant;

class SeedRolesAndPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:roles-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed roles and permissions for all tenants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $seederClass = 'Database\\Seeders\\RolesAndPermissionsSeeder';

        $this->info('Seeding roles and permissions for tenants...');

        // Obtener todos los tenants usando el modelo personalizado
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $this->info("Seeding for tenant: {$tenant->id}");

            // Inicializar el contexto del tenant
            tenancy()->initialize($tenant);

            try {
                // Ejecutar el seeder en el contexto del tenant
                Artisan::call('db:seed', ['--class' => $seederClass]);
                $this->info(Artisan::output());
            } catch (\Exception $e) {
                $this->error("Failed to seed for tenant {$tenant->id}: " . $e->getMessage());
            } finally {
                // Finalizar el contexto del tenant
                tenancy()->end();
            }
        }

        $this->info('Seeding completed for all tenants.');
    }
}
