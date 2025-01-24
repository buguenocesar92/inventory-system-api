<?php

namespace App\Http\Controllers;

use App\Http\Requests\Inventory\InventoryMovementRequest;
use App\Services\InventoryMovementService;
use Illuminate\Http\JsonResponse;

class InventoryMovementController extends Controller
{
    private InventoryMovementService $inventoryMovementService;

    public function __construct(InventoryMovementService $inventoryMovementService)
    {
        $this->inventoryMovementService = $inventoryMovementService;
    }

    /**
     * Listar todos los movimientos de inventario de un producto dado.
     *
     * @param int $productId
     * @return JsonResponse
     */
    public function index(int $productId): JsonResponse
    {
        $movements = $this->inventoryMovementService->getMovementsByProduct($productId);
        return response()->json($movements);
    }

    /**
     * Registrar un movimiento de inventario.
     *
     * @param InventoryMovementRequest $request
     * @return JsonResponse
     */
    public function store(InventoryMovementRequest $request): JsonResponse
    {
        $data = $request->validated();
        $movement = $this->inventoryMovementService->storeMovement($data);

        return response()->json($movement, 201);
    }

}
