<?php

namespace App\Repositories;

use App\Models\Tenant;

class TenantRepository
{
    public function createTenant(string $tenantId, string $domain): Tenant
    {
        $tenant = Tenant::create(['id' => $tenantId]);
        $tenant->domains()->create(['domain' => $domain]);

        return $tenant;
    }
}
