<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Database\Models\Tenant;

class CreateTenant extends Command
{
    protected $signature = 'tenants:create {domain} {--name= : Name of the tenant}';
    protected $description = 'Create a new tenant with a separate database';

    public function handle()
    {
        $domain = $this->argument('domain');
        $name = $this->option('name') ?? 'Unnamed Tenant';

        $databaseName = 'tenant_' . str_replace('.', '_', $domain);

        $tenant = Tenant::create([
            'id' => $domain,
            'data' => [
                'database' => $databaseName,
                'name' => $name,
            ],
        ]);

        $this->info("Tenant created successfully with domain: {$domain}");

        try {
            DB::statement("CREATE DATABASE {$databaseName}");
            $this->info("Database created: {$databaseName}");
        } catch (\Exception $e) {
            $this->error("Failed to create database: {$e->getMessage()}");
            return;
        }

        $tenant->run(function () {
            $this->call('tenants:migrate');
        });

        $this->info("Migrations executed for tenant: {$domain}");
    }
}
