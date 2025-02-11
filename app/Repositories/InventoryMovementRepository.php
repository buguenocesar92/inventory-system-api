<?php

namespace App\Repositories;

use App\Models\InventoryMovement;

class InventoryMovementRepository
{
    public function getByProduct(int $productId)
    {
        return InventoryMovement::with([
            'product',                    // Datos del producto
            'user',                        // Usuario que hizo el movimiento
            'originWarehouse.location',    // Bodega de origen y su local
            'destinationWarehouse.location' // Bodega de destino y su local
        ])
        ->where('product_id', $productId)
        ->orderBy('created_at', 'desc')
        ->get();
    }

    public function create(array $data): InventoryMovement
    {
        return InventoryMovement::create($data);
    }

    public function getByUser(int $userId)
    {
        return InventoryMovement::where('user_id', $userId)->get();
    }

    public function getByWarehouse(int $warehouseId)
    {
        return InventoryMovement::where('origin_warehouse_id', $warehouseId)
            ->orWhere('destination_warehouse_id', $warehouseId)
            ->get();
    }
}
