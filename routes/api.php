<?php
use Illuminate\Support\Facades\Route;
use App\Models\User; // Asegúrate de importar el modelo User

Route::get('/test', function () {
    return 'test api';
});

// Rutas para los tenants
Route::middleware([\Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class])->group(function () {
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
