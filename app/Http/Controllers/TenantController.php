<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenant;
use Spatie\Permission\Models\Role; // Importar el modelo de roles
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

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

        // Crear la base de datos, ejecutar las migraciones y asignar rol al usuario
        $userData = $tenant->run(function () use ($request) {
            // Crear el rol admin si no existe
            if (!Role::where('name', 'admin')->exists()) {
                Role::create(['name' => 'admin']);
            }

            // Crear el usuario
            $user = User::create([
                'name' => $request->user_name,
                'email' => $request->user_email,
                'password' => Hash::make($request->user_password),
            ]);

            // Asignar el rol admin al usuario
            $user->assignRole('admin');

            if (! $token = JWTAuth::fromUser($user)) {
                return response()->json(['error' => 'Could not generate token'], 500);
            }

            return [
                'user' => $user->toArray(),
                'token' => $token,
            ];
        });


     /*    dd($userData); */

        return response()->json([
            'message' => 'Tenant and user created successfully with admin role',
            'tenant_id' => $tenant->id,
            'domain' => $request->domain,
            'user' => $userData['user'], // Ahora es un array
            'token' => $userData['token'],
        ], 201);
    }
}
