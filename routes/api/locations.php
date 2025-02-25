<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\WarehouseController;

Route::group([
    'prefix' => 'locations',
    'middleware' => 'auth:api',
], function () {
    Route::get('/', [LocationController::class, 'index'])->middleware('permission:locations.index');
    Route::post('/', [LocationController::class, 'store'])->middleware('permission:locations.store');
    Route::get('/{id}', [LocationController::class, 'show'])->middleware('permission:locations.show');
    Route::put('/{id}', [LocationController::class, 'update'])->middleware('permission:locations.update');
    Route::delete('/{id}', [LocationController::class, 'destroy'])->middleware('permission:locations.destroy');
    Route::get('/{locationId}/warehouses', [WarehouseController::class, 'getWarehousesByLocation'])
    ->name('warehouses.byLocation')
    ->middleware('permission:warehouses.view');
});
