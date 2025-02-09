<?php

namespace App\Services;

use App\Repositories\InventoryMovementRepository;
use App\Repositories\ProductStockRepository;
use App\Repositories\WarehouseRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\InventoryMovement;
use App\Exceptions\InsufficientStockException;

class InventoryMovementService
{
    private InventoryMovementRepository $inventoryMovementRepo;
    private ProductStockRepository $productStockRepo;
    private WarehouseRepository $warehouseRepo;

    public function __construct(
        InventoryMovementRepository $inventoryMovementRepo,
        ProductStockRepository $productStockRepo,
        WarehouseRepository $warehouseRepo
    ) {
        $this->inventoryMovementRepo = $inventoryMovementRepo;
        $this->productStockRepo = $productStockRepo;
        $this->warehouseRepo = $warehouseRepo;
    }

    /**
     * Procesar movimiento de inventario.
     */
    public function storeMovement(array $data): InventoryMovement
    {
        $user = Auth::user();

        // 🔹 Obtener datos solo cuando sean requeridos
        if ($data['movement_type'] !== 'entry') {
            $originWarehouse = $this->warehouseRepo->findById($data['origin_warehouse_id']);
            $data['origin_location_id'] = $originWarehouse->location_id;
        }

        if ($data['movement_type'] !== 'exit') {
            $destinationWarehouse = $this->warehouseRepo->findById($data['destination_warehouse_id']);
            $data['destination_location_id'] = $destinationWarehouse->location_id;
        }

        $data['user_id'] = $user->id;

        // 🔹 Manejo de stock según tipo de movimiento
        if ($data['movement_type'] === 'entry') {
            $this->productStockRepo->incrementStock($data['product_id'], $data['destination_warehouse_id'], $data['quantity']);
        } elseif ($data['movement_type'] === 'exit') {
            $this->validateStockAvailability($data['product_id'], $data['origin_warehouse_id'], $data['quantity']);
            $this->productStockRepo->decrementStock($data['product_id'], $data['origin_warehouse_id'], $data['quantity']);
        } elseif ($data['movement_type'] === 'transfer') {
            $this->validateStockAvailability($data['product_id'], $data['origin_warehouse_id'], $data['quantity']);
            $this->productStockRepo->decrementStock($data['product_id'], $data['origin_warehouse_id'], $data['quantity']);
            $this->productStockRepo->incrementStock($data['product_id'], $data['destination_warehouse_id'], $data['quantity']);
        }

        return $this->inventoryMovementRepo->create($data);
    }


    /**
     * Validar que hay suficiente stock antes de hacer un movimiento de salida o transferencia.
     */
    private function validateStockAvailability(int $productId, int $warehouseId, int $quantity): void
    {
        $currentStock = $this->productStockRepo->getStock($productId, $warehouseId);

        if (!$currentStock || $currentStock->quantity < $quantity) {
            throw new InsufficientStockException("Stock insuficiente en la bodega ID {$warehouseId} para el producto ID {$productId}.");
        }
    }
}
