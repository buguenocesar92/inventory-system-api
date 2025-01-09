<?php

namespace App\Repositories;

use App\Models\InventoryMovement;

class InventoryMovementRepository
{
    /**
     * Crea un nuevo registro de movimiento de inventario.
     */
    public function create(array $data): InventoryMovement
    {
        return InventoryMovement::create($data);
    }
}
