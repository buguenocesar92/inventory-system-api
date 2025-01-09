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
