<?php

namespace App\Repositories;

use App\Models\ProductStock;
use App\Models\Warehouse;

class ProductStockRepository
{
    /**
     * Obtener el stock de un producto en una bodega específica.
     */
    public function getStock(int $productId, int $warehouseId): ?ProductStock
    {
        return ProductStock::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->first();
    }

    /**
     * Incrementar el stock de un producto en una bodega.
     */
    public function incrementStock(int $productId, int $warehouseId, int $quantity): void
    {
        $stock = ProductStock::firstOrCreate([
            'product_id' => $productId,
            'warehouse_id' => $warehouseId,
        ]);

        $stock->quantity += $quantity;
        $stock->save();
    }

    /**
     * Decrementar el stock de un producto en una bodega.
     */
    public function decrementStock(int $productId, int $warehouseId, int $quantity): void
    {
        $stock = $this->getStock($productId, $warehouseId);

        if (!$stock || $stock->quantity < $quantity) {
            throw new \Exception("No hay suficiente stock en la bodega ID {$warehouseId}.");
        }

        $stock->quantity -= $quantity;
        $stock->save();
    }
}
