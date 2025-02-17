<?php

namespace App\Repositories;

use App\Models\Warehouse;

class WarehouseRepository
{

     /**
     * Obtener una bodega específica por ID.
     */
    public function findById(int $id): ?Warehouse
    {
        return Warehouse::find($id);
    }
    /**
     * Obtener todas las bodegas de un local específico.
     */
    public function getWarehousesByLocation(int $locationId)
    {
        return Warehouse::where('location_id', $locationId)->get();
    }

    /**
     * Obtener una bodega específica por ID, asegurando que pertenece al local.
     */
    public function findByIdAndLocation(int $id, int $locationId): ?Warehouse
    {
        return Warehouse::where('id', $id)
                        ->where('location_id', $locationId)
                        ->first();
    }

    /**
     * Crear una nueva bodega.
     */
    public function create(array $data): Warehouse
    {
        return Warehouse::create($data);
    }

    /**
     * Actualizar los datos de una bodega.
     */
    public function update(Warehouse $warehouse, array $data): Warehouse
    {
        $warehouse->update($data);
        return $warehouse;
    }

    /**
     * Eliminar una bodega.
     */
    public function delete(Warehouse $warehouse): void
    {
        $warehouse->delete();
    }

       /**
     * Obtener todos los almacenes.
     */
    public function getAll()
    {
        return Warehouse::all();
    }

    /**
     * Obtener todos los almacenes y sus localizaciones.
     */
    public function getAllWarehousesWithLocations()
    {
        return Warehouse::with('location')->get();
    }

/**
 * Encuentra la warehouse de ventas en un local, si existe.
 */
public function findSalesWarehouseByLocation(int $locationId)
{
    return Warehouse::where('location_id', $locationId)
        ->where('is_sales_warehouse', true)
        ->first();
}

/**
 * Resetea el flag is_sales_warehouse a false para todas las warehouses de un local,
 * excepto la warehouse con ID $excludeWarehouseId.
 */
public function resetSalesWarehouseForLocation(int $locationId, int $excludeWarehouseId): void
{
    Warehouse::where('location_id', $locationId)
        ->where('id', '!=', $excludeWarehouseId)
        ->update(['is_sales_warehouse' => false]);
}

}
