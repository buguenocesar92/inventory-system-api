<?php

namespace App\Services;

use App\Repositories\TenantRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class TenantService
{
    private TenantRepository $tenantRepository;
    private UserRepository $userRepository;

    public function __construct(TenantRepository $tenantRepository, UserRepository $userRepository)
    {
        $this->tenantRepository = $tenantRepository;
        $this->userRepository = $userRepository;
    }

    public function registerTenant(array $data): array
    {
        $frontendBaseUrl = env('FRONTEND_URL');
        $backendBaseUrl = env('APP_URL');

        $frontendTenantUrl = "http://{$data['tenant_id']}." . parse_url($frontendBaseUrl, PHP_URL_HOST);
        $backendTenantUrl = "{$data['tenant_id']}." . parse_url($backendBaseUrl, PHP_URL_HOST);

        $tenant = $this->tenantRepository->createTenant($data['tenant_id'], $backendTenantUrl);

        $userData = $tenant->run(function () use ($data) {
            // Crear el rol admin si no existe
            if (!Role::where('name', 'admin')->exists()) {
                Role::create(['name' => 'admin']);
            }

            $user = $this->userRepository->create([
                'name' => $data['user_name'],
                'email' => $data['user_email'],
                'password' => Hash::make($data['user_password']),
            ]);

            $user->assignRole('admin');

            $token = JWTAuth::fromUser($user);

            return [
                'user' => $user->toArray(),
                'token' => $token,
            ];
        });

        return [
            'message' => 'Tenant and user created successfully with admin role',
            'tenant_id' => $tenant->id,
            'frontend_url' => $frontendTenantUrl,
            'backend_url' => "{$backendBaseUrl}/api",
            'user' => $userData['user'],
            'access_token' => $userData['token'],
        ];
    }
}
