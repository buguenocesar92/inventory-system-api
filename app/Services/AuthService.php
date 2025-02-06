<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data): array
    {
        $user = $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        return $user->toArray();
    }

    public function login(array $credentials): ?string
    {
        if (!Auth::attempt($credentials)) {
            return null;
        }

        $user = Auth::user()->load('location'); // Cargar la relación de `location`

        // Guardar `location` completa en la sesión
        session(['location' => $user->location]);

        return Auth::tokenById($user->id);
    }

    public function respondWithToken(string $token): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user()->load('location'); // Asegurar que la ubicación se cargue

        return response()->json([
            'access_token' => $token,
            'refresh_token' => auth()->claims(['refresh' => true])->setTTL(config('jwt.refresh_ttl'))->tokenById(auth()->id()),
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'location' => $user->location, // Retorna la información completa del local
            ],
        ]);
    }

}
