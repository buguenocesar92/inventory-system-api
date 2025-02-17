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

    /**
     * Obtener todos los almacenes.
     */
    public function getAllWarehouses()
    {
        return $this->warehouseRepo->getAll();
    }


    /**
     * Obtener todos los almacenes con sus respectivas ubicaciones.
     */
    public function getAllWarehousesWithLocations()
    {
        return $this->warehouseRepo->getAllWarehousesWithLocations();
    }

        /**
     * Establece el valor de is_sales_warehouse para una warehouse.
     * Si se establece en true, se resetea el flag en las demás warehouses del mismo local.
     *
     * @param int $warehouseId
     * @param bool $isSalesWarehouse
     * @return \App\Models\Warehouse
     * @throws ModelNotFoundException
     */
    public function setSalesWarehouse(int $warehouseId, bool $isSalesWarehouse)
    {
        $warehouse = $this->getWarehouseById($warehouseId);

        if ($isSalesWarehouse) {
            // Resetea el flag en todas las warehouses del mismo local (excepto la actual).
            $this->warehouseRepo->resetSalesWarehouseForLocation($warehouse->location_id, $warehouseId);
        }

        // Actualiza la warehouse actual.
        return $this->warehouseRepo->update($warehouse, ['is_sales_warehouse' => $isSalesWarehouse]);
    }
}
