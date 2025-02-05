<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::group([
    'prefix' => 'reports',
    'middleware' => 'auth:api',
], function () {
    Route::get('/sales-history', [ReportController::class, 'salesHistory'])->middleware('permission:reports.sales.view_all'); // Ver historial de ventas completo
    Route::get('/sales-history/daily', [ReportController::class, 'dailySales'])->middleware('permission:reports.sales.view_daily'); // Ver ventas diarias
    Route::get('/sales-history/monthly', [ReportController::class, 'monthlySales'])->middleware('permission:reports.sales.view_monthly'); // Ver ventas mensuales
    Route::get('/sales-history/user/{userId}', [ReportController::class, 'salesByUser'])->middleware('permission:reports.sales.view_by_user'); // Ver ventas por usuario
});
