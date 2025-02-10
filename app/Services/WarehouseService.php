<?php

namespace App\Services;

use App\Repositories\WarehouseRepository;

class WarehouseService
{
    private WarehouseRepository $warehouseRepo;

    public function __construct(WarehouseRepository $warehouseRepo)
    {
        $this->warehouseRepo = $warehouseRepo;
    }

    /**
     * 📌 Obtener todas las bodegas de un local específico.
     */
    public function getWarehousesByLocation(int $locationId)
    {
        return $this->warehouseRepo->getWarehousesByLocation($locationId);
    }
}
