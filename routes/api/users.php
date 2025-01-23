<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;

Route::group([
    'prefix' => 'users',
    'middleware' => 'auth:api',
], function () {
    Route::get('/', [UsersController::class, 'index'])->name('users.index')->middleware('permission:users.index');
    Route::get('/without-roles', [UsersController::class, 'getUsersWithoutRoles'])->name('users.without-roles');
});
