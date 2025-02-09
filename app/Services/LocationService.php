<?php

namespace App\Services;

use App\Repositories\LocationRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LocationService
{
    private LocationRepository $locationRepo;

    public function __construct(LocationRepository $locationRepo)
    {
        $this->locationRepo = $locationRepo;
    }

    /**
     * Obtener todos los locales.
     */
    public function getAllLocations()
    {
        return $this->locationRepo->getAll();
    }

    /**
     * Obtener un local por ID.
     */
    public function getLocationById(int $id)
    {
        $location = $this->locationRepo->findById($id);

        if (!$location) {
            throw new ModelNotFoundException('El local no fue encontrado.');
        }

        return $location;
    }

    /**
     * Crear un nuevo local.
     */
    public function createLocation(array $data)
    {
        return $this->locationRepo->create($data);
    }

    /**
     * Actualizar un local existente.
     */
    public function updateLocation(int $id, array $data)
    {
        $location = $this->getLocationById($id);
        return $this->locationRepo->update($location, $data);
    }

    /**
     * Eliminar un local.
     */
    public function deleteLocation(int $id)
    {
        $location = $this->getLocationById($id);
        $this->locationRepo->delete($location);
    }
}
