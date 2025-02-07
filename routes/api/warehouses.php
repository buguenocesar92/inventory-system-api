<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WarehouseController;

Route::group([
    'prefix' => 'warehouses',
    'middleware' => 'auth:api',
], function () {
    Route::get('/', [WarehouseController::class, 'index'])
        ->name('warehouses.index')
        ->middleware('permission:warehouses.index');

    Route::post('/', [WarehouseController::class, 'store'])
        ->name('warehouses.store')
        ->middleware('permission:warehouses.store');

    Route::get('/{warehouse}', [WarehouseController::class, 'show'])
        ->name('warehouses.show')
        ->middleware('permission:warehouses.show');

    Route::put('/{warehouse}', [WarehouseController::class, 'update'])
        ->name('warehouses.update')
        ->middleware('permission:warehouses.update');

    Route::delete('/{warehouse}', [WarehouseController::class, 'destroy'])
        ->name('warehouses.destroy')
        ->middleware('permission:warehouses.destroy');
});
