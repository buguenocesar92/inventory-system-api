<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;

Route::group([
    'prefix' => 'sales',
    'middleware' => 'auth:api',
], function () {
    Route::post('/', [SaleController::class, 'store'])->middleware('permission:sales.store');
});
