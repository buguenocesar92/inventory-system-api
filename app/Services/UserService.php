<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Obtener todos los usuarios.
     */
    public function getAllUsers()
    {
        return $this->userRepository->getAll();
    }

    public function getUsersWithoutRoles()
    {
        return $this->userRepository->getUsersWithoutRoles();
    }

    public function getAllWithLocations()
    {
        return $this->userRepository->getAllWithLocations();
    }

    /**
     * Obtener un usuario por su ID.
     */
    public function getUserById($id)
    {
        return $this->userRepository->find($id);
    }

    /**
     * Actualizar un usuario.
     */
    public function updateUser($id, array $data)
    {
        return $this->userRepository->update($id, $data);
    }

    /**
     * Eliminar un usuario.
     */
    public function deleteUser($id)
    {
        return $this->userRepository->delete($id);
    }
}
