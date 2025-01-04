<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TenantController;
use App\Models\User; // Asegúrate de importar el modelo User

    // Rutas públicas para registro de inquilinos
    Route::group([
        'prefix' => 'tenants',
    ], function () {
        Route::get('/', function () {
            return 'test api';
        });
        Route::post('/register', [TenantController::class, 'registerTenant'])->name('tenants.register');
    });


    // Rutas para los tenants
    Route::middleware([\Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class])->group(function () {
        Route::group([
            'prefix' => 'auth',
        ], function () {
            Route::post('/register', [AuthController::class, 'register'])->name('register');
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
            Route::post('/login', [AuthController::class, 'login'])->name('login');
            Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
            Route::post('/me', [AuthController::class, 'me'])->name('me');
        });

        Route::get('/', function () {
            return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
        });

        Route::get('/dashboard', function () {
            return 'Dashboard del tenant: ' . tenant('id');
        });

        // Nueva ruta para obtener todos los usuarios
        Route::get('/users', function () {
            return User::all(); // Retorna todos los usuarios del tenant actual
        });
    });
