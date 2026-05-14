<?php

use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    Route::resource('permissions', PermissionController::class);

    Route::resource('roles', RoleController::class);

    Route::get(
        '/roles/{role}/permissions',
        [RoleController::class, 'permissions']
    );
    Route::put(
        '/roles/{role}/permissions',
        [RoleController::class, 'syncPermissions']
    );
});
require __DIR__.'/settings.php';
