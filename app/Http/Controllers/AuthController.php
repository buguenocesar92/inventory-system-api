<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->authService->login($request->validated());

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->authService->respondWithToken($token);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated());
        return response()->json($user, 201);
    }

    public function me(): JsonResponse
    {
        $user = auth()->user()->load('location'); // Cargar el local del usuario

        // Obtener roles y permisos
        $roles = $user->getRoleNames();
        $permissions = $user->getAllPermissions()->pluck('name');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'location' => $user->location, // Agregar la información del local
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh(): JsonResponse
    {
        $token = auth()->getToken();
        $claims = auth()->getPayload($token)->toArray();

        if (!isset($claims['refresh']) || !$claims['refresh']) {
            return response()->json(['error' => 'Invalid refresh token'], 401);
        }

        return $this->authService->respondWithToken(auth()->refresh());
    }

}
