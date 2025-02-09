<?php

namespace App\Http\Controllers;

use App\Services\LocationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    private LocationService $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * Obtener todos los locales.
     */
    public function index(): JsonResponse
    {
        $locations = $this->locationService->getAllLocations();
        return response()->json($locations);
    }

    /**
     * Obtener un local específico.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $location = $this->locationService->getLocationById($id);
            return response()->json($location);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Crear un nuevo local.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'    => 'required|string|unique:locations,name',
            'address' => 'nullable|string',
            'phone'   => 'nullable|string',
            'status'  => 'required|in:active,inactive',
        ]);

        $location = $this->locationService->createLocation($data);

        return response()->json(['message' => 'Local creado con éxito.', 'location' => $location], 201);
    }

    /**
     * Actualizar un local existente.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name'    => 'sometimes|string|unique:locations,name,' . $id,
            'address' => 'nullable|string',
            'phone'   => 'nullable|string',
            'status'  => 'sometimes|in:active,inactive',
        ]);

        try {
            $location = $this->locationService->updateLocation($id, $data);
            return response()->json(['message' => 'Local actualizado con éxito.', 'location' => $location]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Eliminar un local.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->locationService->deleteLocation($id);
            return response()->json(['message' => 'Local eliminado con éxito.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
