<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CashRegisterController;

Route::group([
    'prefix' => 'cash-register',
    'middleware' => 'auth:api',
], function () {
    Route::post('/open', [CashRegisterController::class, 'open'])
        ->name('cash-register.open')
        ->middleware('permission:cash-register.open');

    Route::post('/close', [CashRegisterController::class, 'close'])
        ->name('cash-register.close')
        ->middleware('permission:cash-register.close');


    Route::get('/status', [CashRegisterController::class, 'status'])
        ->name('cash-register.status')
        ->middleware('permission:cash-register.status');
});
