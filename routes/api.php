<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TenantController;
use App\Models\User;

    Route::group([
        'prefix' => 'tenants',
    ], function () {
        Route::get('/', function () {
            return 'test api';
        });
        Route::post('/register', [TenantController::class, 'registerTenant'])->name('tenants.register');
    });


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

        Route::get('/', function () {
            return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
        })->middleware('auth:api');

        Route::get('/dashboard', function () {
            return 'Dashboard del tenant: ' . tenant('id');
        })->middleware('auth:api');

        Route::get('/users', function () {
            return User::all();
        })->middleware('auth:api');
    });
