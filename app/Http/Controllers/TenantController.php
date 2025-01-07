<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tenant\RegisterTenantRequest;
use App\Services\TenantService;
use Illuminate\Http\JsonResponse;

class TenantController extends Controller
{
    private TenantService $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    /**
     * Register a new tenant and its admin user.
     *
     * @param RegisterTenantRequest $request
     * @return JsonResponse
     */
    public function registerTenant(RegisterTenantRequest $request): JsonResponse
    {
        $data = $this->tenantService->registerTenant($request->validated());

        return response()->json($data, 201);
    }
}
