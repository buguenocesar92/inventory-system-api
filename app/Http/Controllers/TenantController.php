<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tenant\RegisterTenantRequest;
use App\Services\TenantService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Tenant",
 *     description="Endpoints relacionados con la gestión de tenants."
 * )
 */
class TenantController extends Controller
{
    private TenantService $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    /**
     * Registrar un nuevo tenant y su usuario administrador.
     *
     * @OA\Post(
     *     path="/tenants/register",
     *     tags={"Tenant"},
     *     summary="Registrar un nuevo tenant",
     *     description="Crea un tenant y su usuario administrador asociado.",
     *     operationId="registerTenant",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"tenant_id", "user_name", "user_email", "user_password"},
     *                 @OA\Property(property="tenant_id", type="string", description="Identificador único del tenant."),
     *                 @OA\Property(property="user_name", type="string", description="Nombre del usuario administrador."),
     *                 @OA\Property(property="user_email", type="string", format="email", description="Correo electrónico del usuario administrador."),
     *                 @OA\Property(
     *                     property="user_password",
     *                     type="string",
     *                     format="password",
     *                     description="Contraseña del usuario administrador. Debe tener al menos 8 caracteres.",
     *                     minLength=8
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tenant registrado con éxito.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Mensaje de éxito."),
     *             @OA\Property(property="tenant_id", type="string", description="ID del tenant creado."),
     *             @OA\Property(property="frontend_url", type="string", description="URL del frontend para el tenant."),
     *             @OA\Property(property="backend_url", type="string", description="URL del backend para el tenant."),
     *             @OA\Property(property="user", type="object", description="Datos del usuario administrador.",
     *                 @OA\Property(property="name", type="string", description="Nombre del administrador."),
     *                 @OA\Property(property="email", type="string", description="Correo electrónico del administrador.")
     *             ),
     *             @OA\Property(property="access_token", type="string", description="Token JWT del usuario.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos inválidos.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Mensaje de error."),
     *             @OA\Property(property="errors", type="object", description="Detalles de los errores.",
     *                 @OA\Property(
     *                     property="user_password",
     *                     type="array",
     *                     @OA\Items(type="string", description="El mensaje de error relacionado con el campo user_password.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function registerTenant(RegisterTenantRequest $request): JsonResponse
    {
        $data = $this->tenantService->registerTenant($request->validated());

        return response()->json($data, 201);
    }
}


