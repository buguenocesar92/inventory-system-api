<?php

namespace App\Repositories;

use App\Models\Warehouse;

class WarehouseRepository
{
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
}
