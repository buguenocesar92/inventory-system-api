<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tenant;

class SeedPosDevices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:pos-devices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed POS devices for all tenants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $seederClass = 'Database\\Seeders\\PosDeviceSeeder';

        $this->info('Seeding POS Devices for tenants...');

        // Obtener todos los tenants
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $this->info("Seeding for tenant: {$tenant->id}");

            // Inicializar el contexto del tenant
            tenancy()->initialize($tenant);

            try {
                // Ejecutar el seeder dentro del tenant
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
