<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductionBundleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/production-bundles', [ProductionBundleController::class, 'index'])->name('production-bundles.index');
    Route::get('/production-bundles/{production_bundle}', [ProductionBundleController::class, 'show'])->name('production-bundles.show');

    Route::middleware('role:admin')->group(function (): void {
        Route::resource('users', UserController::class)->except(['show']);
        Route::post('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
    });

    Route::middleware('role:admin,production')->group(function (): void {
        Route::get('/production-bundles/create', [ProductionBundleController::class, 'create'])->name('production-bundles.create');
        Route::post('/production-bundles', [ProductionBundleController::class, 'store'])->name('production-bundles.store');
        Route::get('/production-bundles/{production_bundle}/edit', [ProductionBundleController::class, 'edit'])->name('production-bundles.edit');
        Route::put('/production-bundles/{production_bundle}', [ProductionBundleController::class, 'update'])->name('production-bundles.update');
        Route::patch('/production-bundles/{production_bundle}', [ProductionBundleController::class, 'update']);
        Route::delete('/production-bundles/{production_bundle}', [ProductionBundleController::class, 'destroy'])->name('production-bundles.destroy');
    });
});
