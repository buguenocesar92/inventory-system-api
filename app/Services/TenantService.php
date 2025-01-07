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
        $frontendTenantUrl = $this->generateTenantUrl($data['tenant_id'], env('FRONTEND_URL'), true);
        $backendTenantUrl = $this->generateTenantUrl($data['tenant_id'], env('APP_URL'), false);

        $tenant = $this->tenantRepository->createTenant($data['tenant_id'], $backendTenantUrl);

        $userData = $tenant->run(function () use ($data) {
            $this->ensureAdminRoleExists();

            $user = $this->createAdminUser($data);

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
            'backend_url' => "{$backendTenantUrl}/api",
            'user' => $userData['user'],
            'access_token' => $userData['token'],
        ];
    }

    /**
     * Ensure the "admin" role exists in the system.
     */
    private function ensureAdminRoleExists(): void
    {
        if (!Role::where('name', 'admin')->exists()) {
            Role::create(['name' => 'admin']);
        }
    }

    /**
     * Create an admin user and assign the "admin" role.
     */
    private function createAdminUser(array $data)
    {
        $user = $this->userRepository->create([
            'name' => $data['user_name'],
            'email' => $data['user_email'],
            'password' => Hash::make($data['user_password']),
        ]);

        $user->assignRole('admin');

        return $user;
    }

    /**
     * Generate a tenant-specific URL based on a base URL and tenant ID.
     */
    private function generateTenantUrl(string $tenantId, string $baseUrl, bool $isFrontend = true): string
    {
        $parsedHost = parse_url($baseUrl, PHP_URL_HOST);
        return $isFrontend ? "http://{$tenantId}.{$parsedHost}" : "{$tenantId}.{$parsedHost}";
    }
}
