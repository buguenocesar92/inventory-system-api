<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tenant;

class SeedWarehouses extends Command
{
    protected $signature = 'seed:warehouses';
    protected $description = 'Seed warehouses for all tenants';

    public function handle()
    {
        $seederClass = 'Database\\Seeders\\WarehouseSeeder';
        $this->info('Seeding warehouses for tenants...');

        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $this->info("Seeding for tenant: {$tenant->id}");

            tenancy()->initialize($tenant);

            try {
                Artisan::call('db:seed', ['--class' => $seederClass]);
                $this->info(Artisan::output());
            } catch (\Exception $e) {
                $this->error("Failed to seed for tenant {$tenant->id}: " . $e->getMessage());
            } finally {
                tenancy()->end();
            }
        }

        $this->info('Seeding completed for all tenants.');
    }
}
