<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryMovementController;


Route::group([
    'prefix' => 'inventory',
    'middleware' => 'auth:api',
], function () {
    Route::post('/movements', [InventoryMovementController::class, 'store'])->name('inventory.movements.store')->middleware('permission:inventory.movements.store');
});
