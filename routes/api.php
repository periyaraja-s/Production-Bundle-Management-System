<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProductionBundleController;
use Illuminate\Support\Facades\Route;

Route::get('/bundles', [ProductionBundleController::class, 'index']);
Route::post('/bundles', [ProductionBundleController::class, 'store']);
Route::put('/bundles/{bundle}', [ProductionBundleController::class, 'update']);
Route::delete('/bundles/{bundle}', [ProductionBundleController::class, 'destroy']);
Route::get('/dashboard', [DashboardController::class, 'index']);
