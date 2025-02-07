<?php

namespace App\Repositories;

use App\Models\PosDevice;

class PosDeviceRepository
{
    /**
     * Verificar si un POS pertenece a un local específico
     */
    public function existsInLocation(int $posDeviceId, int $locationId): bool
    {
        return PosDevice::where('id', $posDeviceId)
            ->where('location_id', $locationId)
            ->exists();
    }
}
