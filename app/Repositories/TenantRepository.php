<?php

namespace App\Repositories;

use App\Models\Tenant;
/**
 * Repositorio para manejar operaciones de tenants.
 */
class TenantRepository
{
    /**
     * Crear un nuevo tenant y asociar su dominio.
     *
     * @param string $tenantId Identificador del tenant.
     * @param string $domain Dominio asociado.
     * @return Tenant Tenant creado.
     */
    public function createTenant(string $tenantId, string $domain): Tenant
    {
        $tenant = Tenant::create(['id' => $tenantId]);
        $tenant->domains()->create(['domain' => $domain]);

        return $tenant;
    }
}
