<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::group([
    'prefix' => 'products',
    'middleware' => 'auth:api',
], function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index')->middleware('permission:products.index');
    Route::post('/', [ProductController::class, 'store'])->name('products.store')->middleware('permission:products.store');
    Route::get('/{product}', [ProductController::class, 'show'])->name('products.show')->middleware('permission:products.show');
    Route::put('/{product}', [ProductController::class, 'update'])->name('products.update')->middleware('permission:products.update');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('permission:products.destroy');
    Route::get('/barcode/{barcode}', [ProductController::class, 'showByBarcode'])->middleware('permission:products.showByBarcode');
});
