<?php

namespace App\Repositories;

use App\Models\InventoryMovement;

class InventoryMovementRepository
{
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
