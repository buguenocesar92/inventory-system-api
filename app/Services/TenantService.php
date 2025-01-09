<?php

namespace App\Services;

use App\Repositories\TenantRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
/**
 * Servicio para manejar operaciones relacionadas con tenants.
 */
class TenantService
{
    private TenantRepository $tenantRepository;
    private UserRepository $userRepository;

    public function __construct(TenantRepository $tenantRepository, UserRepository $userRepository)
    {
        $this->tenantRepository = $tenantRepository;
        $this->userRepository = $userRepository;
    }
    /**
     * Registrar un tenant y su usuario administrador.
     *
     * @param array $data Datos para crear el tenant y el usuario.
     * @return array Respuesta con información del tenant y usuario.
     */
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
     * Asegurar que el rol de "admin" exista en el sistema.
     */
    private function ensureAdminRoleExists(): void
    {
        if (!Role::where('name', 'admin')->exists()) {
            Role::create(['name' => 'admin']);
        }
    }

    /**
     * Crear un usuario administrador y asignarle el rol "admin".
     *
     * @param array $data Datos del usuario.
     * @return \App\Models\User Usuario creado.
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
     * Generar una URL específica para el tenant.
     *
     * @param string $tenantId ID del tenant.
     * @param string $baseUrl URL base.
     * @param bool $isFrontend Si la URL es para el frontend.
     * @return string URL generada.
     */
    private function generateTenantUrl(string $tenantId, string $baseUrl, bool $isFrontend = true): string
    {
        $parsedHost = parse_url($baseUrl, PHP_URL_HOST);
        return $isFrontend ? "http://{$tenantId}.{$parsedHost}" : "{$tenantId}.{$parsedHost}";
    }
}
