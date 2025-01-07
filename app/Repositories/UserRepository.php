<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    private User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function create(array $data): User
    {
        return $this->model->create($data);
    }
}
