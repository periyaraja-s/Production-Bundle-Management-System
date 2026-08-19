<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductionBundle;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $today = now()->toDateString();

        $metrics = ProductionBundle::query()
            ->selectRaw('COUNT(*) as total_bundles')
            ->selectRaw('COALESCE(SUM(quantity), 0) as total_quantity')
            ->selectRaw('COALESCE(SUM(completed_qty), 0) as total_completed')
            ->selectRaw('COALESCE(SUM(rejected_qty), 0) as total_rejected')
            ->selectRaw('COALESCE(AVG(CASE WHEN quantity > 0 THEN completed_qty * 100.0 / quantity END), 0) as average_efficiency')
            ->selectRaw('COALESCE(SUM(CASE WHEN production_date = ? THEN quantity ELSE 0 END), 0) as today_production', [$today])
            ->selectRaw('COALESCE(SUM(CASE WHEN production_date = ? THEN rejected_qty ELSE 0 END), 0) as today_rejection', [$today])
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'total_bundles' => (int) $metrics->total_bundles,
                'total_quantity' => (int) $metrics->total_quantity,
                'total_completed' => (int) $metrics->total_completed,
                'total_rejected' => (int) $metrics->total_rejected,
                'average_efficiency' => round((float) $metrics->average_efficiency, 2),
                'today_production' => (int) $metrics->today_production,
                'today_rejection' => (int) $metrics->today_rejection,
            ],
        ]);
    }
}
