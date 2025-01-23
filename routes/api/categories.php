<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::group(['prefix' => 'categories', 'middleware' => 'auth:api'], function () {
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index')->middleware('permission:categories.index');
    Route::post('/', [CategoryController::class, 'store'])->name('categories.store')->middleware('permission:categories.store');
    Route::get('/{category}', [CategoryController::class, 'show'])->name('categories.show')->middleware('permission:categories.show');
    Route::put('/{category}', [CategoryController::class, 'update'])->name('categories.update')->middleware('permission:categories.update');
    Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy')->middleware('permission:categories.destroy');
});
