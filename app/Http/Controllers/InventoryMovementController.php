<?php

namespace App\Http\Controllers;

use App\Http\Requests\Inventory\StoreInventoryMovementRequest;
use App\Services\InventoryMovementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class InventoryMovementController extends Controller
{
    private InventoryMovementService $inventoryMovementService;

    public function __construct(InventoryMovementService $inventoryMovementService)
    {
        $this->inventoryMovementService = $inventoryMovementService;
    }

    public function index(int $productId): JsonResponse
    {
        $movements = $this->inventoryMovementService->getMovementsByProduct($productId);
        return response()->json($movements);
    }


    public function store(StoreInventoryMovementRequest $request): JsonResponse
    {
        try {
            $movement = $this->inventoryMovementService->storeMovement($request->validated());

            return response()->json([
                'message' => 'Movimiento registrado exitosamente.',
                'movement' => $movement,
                'user' => Auth::user(), // Retorna usuario que hizo el movimiento
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
