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

    /**
     * Obtener todos los almacenes.
     */
    public function index(): JsonResponse
    {
        $warehouses = $this->warehouseService->getAllWarehouses();
        return response()->json($warehouses);
    }

    /**
     * Obtener un almacén específico.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $warehouse = $this->warehouseService->getWarehouseById($id);
            return response()->json($warehouse);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Crear un nuevo almacén.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'location_id' => 'required|integer|exists:locations,id',
            'name'        => 'required|string|unique:warehouses,name',
            'type'        => 'required|string',
        ]);

        $warehouse = $this->warehouseService->createWarehouse($data);

        return response()->json(['message' => 'Almacén creado con éxito.', 'warehouse' => $warehouse], 201);
    }

    /**
     * Actualizar un almacén existente.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'location_id' => 'sometimes|integer|exists:locations,id',
            'name'        => 'sometimes|string|unique:warehouses,name,' . $id,
            'type'        => 'sometimes|string',
        ]);

        try {
            $warehouse = $this->warehouseService->updateWarehouse($id, $data);
            return response()->json(['message' => 'Almacén actualizado con éxito.', 'warehouse' => $warehouse]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Eliminar un almacén.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->warehouseService->deleteWarehouse($id);
            return response()->json(['message' => 'Almacén eliminado con éxito.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

}
