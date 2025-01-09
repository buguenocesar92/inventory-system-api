<?php

namespace App\Repositories;

use App\Models\User;
/**
 * Repositorio para manejar operaciones de usuarios.
 */
class UserRepository
{
    private User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }
    /**
     * Crear un usuario.
     *
     * @param array $data Datos del usuario.
     * @return User Usuario creado.
     */
    public function create(array $data): User
    {
        return $this->model->create($data);
    }

}
