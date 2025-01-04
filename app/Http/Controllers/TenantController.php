<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenant; // Usa el modelo correcto
use Stancl\Tenancy\Database\Models\Domain;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TenantController extends Controller
{
    public function registerTenant(Request $request)
    {

    $validator = Validator::make(request()->all(), [
        'tenant_id' => 'required|string|unique:tenants,id',
        'domain' => 'required|string|unique:domains,domain',
        'user_name' => 'required|string|max:255',
        'user_email' => 'required|email|unique:users,email',
        'user_password' => 'required|string|min:8',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors()->toJson(), 400);
    }
        // Crear el tenant
        $tenant = Tenant::create(['id' => $request->tenant_id]);

        // Crear el dominio para el tenant
        $tenant->domains()->create(['domain' => $request->domain]);

        // Crear la base de datos y ejecutar las migraciones del tenant
        $tenant->run(function () use ($request) {
            User::create([
                'name' => $request->user_name,
                'email' => $request->user_email,
                'password' => Hash::make($request->user_password),
            ]);
        });

        return response()->json([
            'message' => 'Tenant and user created successfully',
            'tenant_id' => $tenant->id,
            'domain' => $request->domain,
        ], 201);
    }
}
