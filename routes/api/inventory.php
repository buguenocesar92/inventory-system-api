<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryMovementController;

Route::group([
    'prefix' => 'inventory',
    'middleware' => 'auth:api',
], function () {
    // Registrar un nuevo movimiento
    Route::post('/movements', [InventoryMovementController::class, 'store'])
        ->name('inventory.movements.store')
        ->middleware('permission:inventory.movements.store');

    // Obtener movimientos de un producto específico
    Route::get('/products/{product}/movements', [InventoryMovementController::class, 'index'])
        ->name('inventory.movements.index')
        ->middleware('permission:inventory.movements.index');
});
