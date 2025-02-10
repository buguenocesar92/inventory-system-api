<?php

namespace App\Http\Controllers;

use App\Services\WarehouseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    private WarehouseService $warehouseService;

    public function __construct(WarehouseService $warehouseService)
    {
        $this->warehouseService = $warehouseService;
    }

    /**
     * 📌 Obtener todas las bodegas de un local específico.
     */
    public function getWarehousesByLocation(int $locationId): JsonResponse
    {
        try {
            $warehouses = $this->warehouseService->getWarehousesByLocation($locationId);
            return response()->json($warehouses, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener las bodegas'], 500);
        }
    }
}
