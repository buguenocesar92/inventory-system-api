<?php

namespace App\Http\Controllers;

use App\Http\Requests\Warehouse\CreateWarehouseRequest;
use App\Http\Requests\Warehouse\UpdateWarehouseRequest;
use App\Repositories\WarehouseRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Warehouse;

class WarehouseController extends Controller
{
    private WarehouseRepository $warehouseRepo;

    public function __construct(WarehouseRepository $warehouseRepo)
    {
        $this->warehouseRepo = $warehouseRepo;
    }

    /**
     * Listar las bodegas del local al que pertenece el usuario.
     */
    public function index(): JsonResponse
    {
        $locationId = Auth::user()->location_id;
        $warehouses = $this->warehouseRepo->getWarehousesByLocation($locationId);

        return response()->json($warehouses);
    }

    /**
     * Mostrar una bodega específica, asegurando que pertenece al local del usuario.
     */
    public function show(int $id): JsonResponse
    {
        $locationId = Auth::user()->location_id;
        $warehouse = $this->warehouseRepo->findByIdAndLocation($id, $locationId);

        if (!$warehouse) {
            return response()->json(['error' => 'Bodega no encontrada o no pertenece a este local.'], 404);
        }

        return response()->json($warehouse);
    }

    /**
     * Crear una nueva bodega dentro del local del usuario.
     */
    public function store(CreateWarehouseRequest $request): JsonResponse
    {
        $locationId = Auth::user()->location_id;

        $warehouse = $this->warehouseRepo->create([
            'name' => $request->name,
            'location_id' => $locationId,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Bodega creada exitosamente.', 'warehouse' => $warehouse], 201);
    }

    /**
     * Actualizar la información de una bodega.
     */
    public function update(UpdateWarehouseRequest $request, int $id): JsonResponse
    {
        $locationId = Auth::user()->location_id;
        $warehouse = $this->warehouseRepo->findByIdAndLocation($id, $locationId);

        if (!$warehouse) {
            return response()->json(['error' => 'Bodega no encontrada o no pertenece a este local.'], 404);
        }

        $updatedWarehouse = $this->warehouseRepo->update($warehouse, $request->validated());

        return response()->json(['message' => 'Bodega actualizada exitosamente.', 'warehouse' => $updatedWarehouse]);
    }

    /**
     * Eliminar una bodega.
     */
    public function destroy(int $id): JsonResponse
    {
        $locationId = Auth::user()->location_id;
        $warehouse = $this->warehouseRepo->findByIdAndLocation($id, $locationId);

        if (!$warehouse) {
            return response()->json(['error' => 'Bodega no encontrada o no pertenece a este local.'], 404);
        }

        $this->warehouseRepo->delete($warehouse);

        return response()->json(['message' => 'Bodega eliminada exitosamente.']);
    }
}
