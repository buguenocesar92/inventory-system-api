<?php

namespace App\Repositories;

use App\Models\ProductStock;
use App\Models\Warehouse;

class ProductStockRepository
{
    public function getStock(int $productId, int $warehouseId): ?ProductStock
    {
        return ProductStock::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->first();
    }

    public function updateStock(int $productId, int $warehouseId, int $newQuantity): void
    {
        ProductStock::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->update(['quantity' => $newQuantity]);
    }

    public function validateWarehouseLocation(int $warehouseId, int $locationId): bool
    {
        return Warehouse::where('id', $warehouseId)
            ->where('location_id', $locationId)
            ->exists();
    }
}
