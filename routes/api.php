<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProductionBundleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn (Request $request) => $request->user());
    Route::get('/bundles', [ProductionBundleController::class, 'index']);
    Route::post('/bundles', [ProductionBundleController::class, 'store']);
    Route::put('/bundles/{bundle}', [ProductionBundleController::class, 'update']);
    Route::delete('/bundles/{bundle}', [ProductionBundleController::class, 'destroy']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
