<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryMovementController;
use App\Models\User;

// Rutas para la gestión de tenants
Route::group([
    'prefix' => 'tenants',
], function () {
    Route::get('/', function () {
        return 'Test API for tenants';
    });
    Route::post('/register', [TenantController::class, 'registerTenant'])->name('tenants.register');
});

// Rutas para tenants, asegurando el contexto multi-tenant
Route::middleware([\Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class])->group(function () {

    // Rutas de autenticación
    Route::group([
        'prefix' => 'auth',
    ], function () {
        Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
        Route::post('/register', [AuthController::class, 'register'])->name('auth.register')->middleware('auth:api');
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth:api');
        Route::post('/refresh', [AuthController::class, 'refresh'])->name('auth.refresh')->middleware('auth:api');
        Route::post('/me', [AuthController::class, 'me'])->name('auth.me')->middleware('auth:api');
    });

    // Ruta de prueba para verificar el tenant actual
    Route::get('/', function () {
        return 'This is your multi-tenant application. The ID of the current tenant is ' . tenant('id');
    })->middleware('auth:api');

    Route::get('/dashboard', function () {
        return 'Dashboard for tenant: ' . tenant('id');
    })->middleware('auth:api');

    // Ruta para obtener todos los usuarios del tenant actual
    Route::get('/users', function () {
        return User::all();
    })->middleware('auth:api');

    // Grupo de rutas para productos
    Route::group([
        'prefix' => 'products',
        'middleware' => 'auth:api',
    ], function () {
        Route::get('/', [ProductController::class, 'index'])->name('products.index');
        Route::post('/', [ProductController::class, 'store'])->name('products.store');
        Route::get('/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::put('/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    // Grupo de rutas para movimientos de inventario
    Route::group([
        'prefix' => 'inventory',
        'middleware' => 'auth:api',
    ], function () {
        Route::post('/movements', [InventoryMovementController::class, 'store'])->name('inventory.movements.store');
    });
});
