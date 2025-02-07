<?php
use Illuminate\Support\Facades\Route;

require __DIR__ . '/api/tenants.php';

Route::middleware([\Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class])->group(function () {
    require __DIR__ . '/api/auth.php';
    require __DIR__ . '/api/roles.php';
    require __DIR__ . '/api/permissions.php';
    require __DIR__ . '/api/users.php';
    require __DIR__ . '/api/products.php';
    require __DIR__ . '/api/inventory.php';
    require __DIR__ . '/api/sales.php';
    require __DIR__ . '/api/cash_register.php';
    require __DIR__ . '/api/categories.php';
    require __DIR__ . '/api/reports.php';
    require __DIR__ . '/api/warehouses.php';
});
