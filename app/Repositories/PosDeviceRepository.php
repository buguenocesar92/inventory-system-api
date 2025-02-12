<?php

namespace App\Repositories;

use App\Models\PosDevice;

class PosDeviceRepository
{
    /**
     * Verificar si un POS pertenece a un local específico
     */
    public function existsInLocation(int $locationId): bool
    {
        return PosDevice::where('location_id', $locationId)
            ->exists();
    }
}
