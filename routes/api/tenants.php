<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantController;

Route::group([
    'prefix' => 'tenants',
], function () {
    Route::post('/register', [TenantController::class, 'registerTenant'])->name('tenants.register');
});
