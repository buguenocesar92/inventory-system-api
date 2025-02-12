<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Listar todos los usuarios.
     */
    public function index(): JsonResponse
    {
        $users = $this->userService->getAllUsers();
        return response()->json($users);
    }

    /**
     * Obtener usuarios sin roles asignados.
     */
    public function getUsersWithoutRoles(): JsonResponse
    {
        $users = $this->userService->getUsersWithoutRoles();
        return response()->json($users);
    }

    /**
     * Obtener todos los usuarios con sus ubicaciones.
     */
    public function getAllWithLocations(): JsonResponse
    {
        $users = $this->userService->getAllWithLocations();
        return response()->json($users);
    }

    /**
     * Mostrar un usuario en específico.
     */
    public function show($id): JsonResponse
    {
        $user = $this->userService->getUserById($id);
        return response()->json($user);
    }

    /**
     * Actualizar un usuario.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->all();
        $user = $this->userService->updateUser($id, $data);
        return response()->json($user);
    }

    /**
     * Eliminar un usuario.
     */
    public function destroy($id): JsonResponse
    {
        $this->userService->deleteUser($id);
        return response()->json(['message' => 'Usuario eliminado correctamente']);
    }
}
