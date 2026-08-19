<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductionBundleRequest;
use App\Models\ProductionBundle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductionBundleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = in_array((int) $request->input('per_page', 20), [20, 50, 100], true)
            ? (int) $request->input('per_page', 20)
            : 20;

        $bundles = ProductionBundle::query()
            ->with(['buyer', 'style', 'sewingLine'])
            ->latest('production_date')
            ->latest('id')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $bundles->items(),
            'meta' => [
                'current_page' => $bundles->currentPage(),
                'per_page' => $bundles->perPage(),
                'total' => $bundles->total(),
                'last_page' => $bundles->lastPage(),
                'from' => $bundles->firstItem(),
                'to' => $bundles->lastItem(),
            ],
        ]);
    }

    public function store(ProductionBundleRequest $request): JsonResponse
    {
        $bundle = ProductionBundle::query()->create($request->validated());
        $bundle->load(['buyer', 'style', 'sewingLine']);

        return response()->json([
            'success' => true,
            'message' => 'Production bundle created successfully.',
            'data' => $bundle,
        ], 201);
    }

    public function update(ProductionBundleRequest $request, ProductionBundle $bundle): JsonResponse
    {
        $bundle->update($request->validated());
        $bundle->load(['buyer', 'style', 'sewingLine']);

        return response()->json([
            'success' => true,
            'message' => 'Production bundle updated successfully.',
            'data' => $bundle,
        ]);
    }

    public function destroy(ProductionBundle $bundle): JsonResponse
    {
        $bundle->delete();

        return response()->json([
            'success' => true,
            'message' => 'Production bundle deleted successfully.',
        ]);
    }
}
