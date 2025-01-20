<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryMovementController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RoleController;

Route::group([
    'prefix' => 'tenants',
], function () {
    Route::post('/register', [TenantController::class, 'registerTenant'])->name('tenants.register');
});

// Grupo de rutas para autenticación
Route::middleware([\Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class])->group(function () {
    Route::group([
        'prefix' => 'auth',
    ], function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/register', [AuthController::class, 'register'])->name('register')->middleware('auth:api');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:api');
        Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh')->middleware('auth:api');
        Route::post('/me', [AuthController::class, 'me'])->name('me')->middleware('auth:api');
    });

    // Grupo de rutas para roles
    Route::group([
        'prefix' => 'roles',
        'middleware' => 'auth:api',
    ], function () {
        Route::get('/with-permissions', [RolePermissionController::class, 'index'])->name('roles.with-permissions')->middleware('permission:roles.with-permissions');
        Route::get('/with-permissions/{roleId}', [RolePermissionController::class, 'show'])->name('role.show')->middleware('permission:roles.with-permissions.show');
        Route::put('/with-permissions/{roleId}', [RolePermissionController::class, 'updateRolePermissions'])->name('roles.update-permissions')->middleware('permission:roles.update-permissions');
        Route::put('/{role}/users', [RoleController::class, 'updateUsers']);
    });

    Route::group([
        'prefix' => 'permissions',
        'middleware' => 'auth:api',
    ], function () {
        Route::get('/', [PermissionController::class, 'index'])->name('permission.index')->middleware('permission:permission.index');
    });

    Route::group([
        'prefix' => 'users',
        'middleware' => 'auth:api',
    ], function () {
        Route::get('/', [UsersController::class, 'index'])->name('users.index')->middleware('permission:users.index');
    });

    // Grupo de rutas para productos
    Route::group([
        'prefix' => 'products',
        'middleware' => 'auth:api',
    ], function () {
        Route::get('/', [ProductController::class, 'index'])->name('products.index')->middleware('permission:products.index');
        Route::post('/', [ProductController::class, 'store'])->name('products.store')->middleware('permission:products.store');
        Route::get('/{product}', [ProductController::class, 'show'])->name('products.show')->middleware('permission:products.show');
        Route::put('/{product}', [ProductController::class, 'update'])->name('products.update')->middleware('permission:products.update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('permission:products.destroy');
        Route::get('/barcode/{barcode}', [ProductController::class, 'showByBarcode'])->middleware('permission:products.showByBarcode');
    });

    // Grupo de rutas para movimientos de inventario
    Route::group([
        'prefix' => 'inventory',
        'middleware' => 'auth:api',
    ], function () {
        Route::post('/movements', [InventoryMovementController::class, 'store'])->name('inventory.movements.store')->middleware('permission:inventory.movements.store');
    });

    // Grupo de rutas para ventas
    Route::group([
        'prefix' => 'sales',
        'middleware' => 'auth:api',
    ], function () {
        Route::post('/', [SaleController::class, 'store'])->middleware('permission:sales.store');
    });

});
