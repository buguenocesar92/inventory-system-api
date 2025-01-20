<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\JsonResponse;

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
}
