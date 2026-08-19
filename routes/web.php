<?php

use App\Http\Controllers\ProductionBundleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('production-bundles', ProductionBundleController::class);
