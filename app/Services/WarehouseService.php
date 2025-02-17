<?php

namespace App\Services;

use App\Repositories\WarehouseRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WarehouseService
{
    private WarehouseRepository $warehouseRepo;

    public function __construct(WarehouseRepository $warehouseRepo)
    {
        $this->warehouseRepo = $warehouseRepo;
    }

    /**
     * 📌 Obtener todas las bodegas de un local específico.
     */
    public function getWarehousesByLocation(int $locationId)
    {
        return $this->warehouseRepo->getWarehousesByLocation($locationId);
    }

        /**
     * Obtener todos los almacenes.
     */
    public function getAllWarehouses()
    {
        return $this->warehouseRepo->getAll();
    }

    /**
     * Obtener un almacén por ID.
     */
    public function getWarehouseById(int $id)
    {
        $warehouse = $this->warehouseRepo->findById($id);

        if (!$warehouse) {
            throw new ModelNotFoundException('El almacén no fue encontrado.');
        }

        return $warehouse;
    }

    /**
     * Crear un nuevo almacén.
     */
    public function createWarehouse(array $data)
    {
        return $this->warehouseRepo->create($data);
    }

    /**
     * Actualizar un almacén existente.
     */
    public function updateWarehouse(int $id, array $data)
    {
        $warehouse = $this->getWarehouseById($id);
        return $this->warehouseRepo->update($warehouse, $data);
    }

    /**
     * Eliminar un almacén.
     */
    public function deleteWarehouse(int $id)
    {
        $warehouse = $this->getWarehouseById($id);
        $this->warehouseRepo->delete($warehouse);
    }
}
