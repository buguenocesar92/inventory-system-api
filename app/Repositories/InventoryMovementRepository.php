<?php

namespace App\Repositories;

use App\Models\InventoryMovement;

class InventoryMovementRepository
{
    /**
     * Obtener todos los movimientos de un producto
     * (podrías agregar paginación, orden, etc. según requieras).
     */
    public function getByProduct(int $productId)
    {
        return InventoryMovement::where('product_id', $productId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Crea un nuevo registro de movimiento de inventario.
     */
    public function create(array $data): InventoryMovement
    {
        return InventoryMovement::create($data);
    }
}
