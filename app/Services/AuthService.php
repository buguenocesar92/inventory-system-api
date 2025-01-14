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
        return Auth::attempt($credentials) ? Auth::tokenById(Auth::id()) : null;
    }

    public function respondWithToken(string $token): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'refresh_token' => auth()->claims(['refresh' => true])->setTTL(config('jwt.refresh_ttl'))->tokenById(auth()->id()),
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
        ]);
    }

}
