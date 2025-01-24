<?php

namespace App\Services;

use App\Repositories\InventoryMovementRepository;
use App\Models\Product;
use App\Exceptions\InsufficientStockException;

class InventoryMovementService
{
    private InventoryMovementRepository $inventoryMovementRepository;

    public function __construct(InventoryMovementRepository $inventoryMovementRepository)
    {
        $this->inventoryMovementRepository = $inventoryMovementRepository;
    }

    /**
     * Obtener movimientos de inventario de un producto específico.
     *
     * @param int $productId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMovementsByProduct(int $productId)
    {
        // Aquí podrías validarlo si quieres asegurarte de que el producto exista, p.ej.:
        // Product::findOrFail($productId);

        return $this->inventoryMovementRepository->getByProduct($productId);
    }

    /**
     * Procesa el registro de un movimiento de inventario.
     *
     * @param array $data Datos validados del movimiento.
     * @return \App\Models\InventoryMovement
     * @throws InsufficientStockException
     */
    public function storeMovement(array $data)
    {
        // Verificar producto
        $product = Product::findOrFail($data['product_id']);

        // Verificar stock para 'exit'
        if ($data['movement_type'] === 'exit' && $product->current_stock < $data['quantity']) {
            throw new InsufficientStockException();
        }

        // Crear el movimiento
        $movement = $this->inventoryMovementRepository->create($data);

        // Actualizar stock
        if ($data['movement_type'] === 'entry') {
            $product->current_stock += $data['quantity'];
        } elseif ($data['movement_type'] === 'exit') {
            $product->current_stock -= $data['quantity'];
        }
        // Para "adjustment", define la lógica que necesites.

        // Guardar cambios en producto
        $product->save();

        return $movement;
    }
}
