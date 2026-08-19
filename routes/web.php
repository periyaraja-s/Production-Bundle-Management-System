<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductionBundleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('production-bundles', ProductionBundleController::class);
