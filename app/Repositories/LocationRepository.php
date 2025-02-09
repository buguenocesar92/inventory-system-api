<?php

namespace App\Repositories;

use App\Models\Location;

class LocationRepository
{
    /**
     * Obtener todos los locales.
     */
    public function getAll()
    {
        return Location::all();
    }

    /**
     * Obtener un local por ID.
     */
    public function findById(int $id): ?Location
    {
        return Location::find($id);
    }

    /**
     * Crear un nuevo local.
     */
    public function create(array $data): Location
    {
        return Location::create($data);
    }

    /**
     * Actualizar un local existente.
     */
    public function update(Location $location, array $data): Location
    {
        $location->update($data);
        return $location;
    }

    /**
     * Eliminar un local.
     */
    public function delete(Location $location): void
    {
        $location->delete();
    }
}
