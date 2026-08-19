<?php

namespace App\Http\Controllers;

use App\Models\ProductionBundle;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
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

        return view('dashboard', compact('metrics'));
    }
}
