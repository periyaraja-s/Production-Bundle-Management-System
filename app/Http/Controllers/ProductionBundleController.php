<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductionBundleRequest;
use App\Models\Buyer;
use App\Models\ProductionBundle;
use App\Models\SewingLine;
use App\Models\Style;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductionBundleController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = in_array((int) $request->input('per_page', 20), [20, 50, 100], true)
            ? (int) $request->input('per_page', 20)
            : 20;
        $sort = $request->input('sort', 'production_date');
        $direction = $request->input('direction') === 'asc' ? 'asc' : 'desc';

        $productionBundles = ProductionBundle::query()
            ->with(['buyer', 'style', 'sewingLine'])
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = '%'.$request->input('search').'%';

                $query->where(function ($query) use ($search): void {
                    $query->where('bundle_no', 'like', $search)
                        ->orWhere('operator_name', 'like', $search)
                        ->orWhere('color', 'like', $search)
                        ->orWhereHas('buyer', fn ($query) => $query->where('buyer_name', 'like', $search))
                        ->orWhereHas('style', fn ($query) => $query->where('style_no', 'like', $search));
                });
            })
            ->when($request->filled('buyer_id'), fn ($query) => $query->where('buyer_id', $request->input('buyer_id')))
            ->when($request->filled('style_id'), fn ($query) => $query->where('style_id', $request->input('style_id')))
            ->when($request->filled('line_id'), fn ($query) => $query->where('line_id', $request->input('line_id')))
            ->when($request->filled('date_from'), fn ($query) => $query->where('production_date', '>=', $request->input('date_from')))
            ->when($request->filled('date_to'), fn ($query) => $query->where('production_date', '<=', $request->input('date_to')));

        match ($sort) {
            'bundle_no', 'quantity' => $productionBundles->orderBy($sort, $direction),
            'production_date' => $productionBundles->orderBy('production_date', $direction)->orderBy('id', $direction),
            'buyer' => $productionBundles->orderBy(
                Buyer::query()->select('buyer_name')->whereColumn('buyers.id', 'production_bundles.buyer_id'),
                $direction,
            ),
            'style' => $productionBundles->orderBy(
                Style::query()->select('style_no')->whereColumn('styles.id', 'production_bundles.style_id'),
                $direction,
            ),
            'efficiency' => $productionBundles->orderByRaw(
                'completed_qty * 100.0 / nullif(quantity, 0) '.$direction,
            ),
            default => $productionBundles->orderByDesc('production_date')->orderByDesc('id'),
        };

        $productionBundles = $productionBundles->paginate($perPage)->withQueryString();

        return view('production-bundles.index', array_merge(
            compact('productionBundles', 'sort', 'direction', 'perPage'),
            $this->formOptions(withStyleBuyer: false),
        ));
    }

    public function create(): View
    {
        return view('production-bundles.create', $this->formOptions());
    }

    public function store(ProductionBundleRequest $request): JsonResponse|RedirectResponse
    {
        $productionBundle = ProductionBundle::query()->create($request->validated());

        if ($request->expectsJson()) {
            session()->flash('success', 'Production bundle created successfully.');

            return response()->json([
                'message' => 'Production bundle created successfully.',
                'redirect_url' => route('production-bundles.show', $productionBundle),
            ]);
        }

        return redirect()
            ->route('production-bundles.show', $productionBundle)
            ->with('success', 'Production bundle created successfully.');
    }

    public function show(ProductionBundle $productionBundle): View
    {
        $productionBundle->load(['buyer', 'style', 'sewingLine']);

        return view('production-bundles.show', compact('productionBundle'));
    }

    public function edit(ProductionBundle $productionBundle): View
    {
        return view('production-bundles.edit', array_merge(
            compact('productionBundle'),
            $this->formOptions(),
        ));
    }

    public function update(ProductionBundleRequest $request, ProductionBundle $productionBundle): JsonResponse|RedirectResponse
    {
        $productionBundle->update($request->validated());

        if ($request->expectsJson()) {
            session()->flash('success', 'Production bundle updated successfully.');

            return response()->json([
                'message' => 'Production bundle updated successfully.',
                'redirect_url' => route('production-bundles.show', $productionBundle),
            ]);
        }

        return redirect()
            ->route('production-bundles.show', $productionBundle)
            ->with('success', 'Production bundle updated successfully.');
    }

    public function destroy(ProductionBundle $productionBundle): RedirectResponse
    {
        $productionBundle->delete();

        return redirect()
            ->route('production-bundles.index')
            ->with('success', 'Production bundle deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formOptions(bool $withStyleBuyer = true): array
    {
        return [
            'buyers' => Buyer::query()->orderBy('buyer_name')->get(),
            'styles' => Style::query()
                ->when($withStyleBuyer, fn ($query) => $query->with('buyer'))
                ->orderBy('style_no')
                ->get(),
            'sewingLines' => SewingLine::query()->orderBy('line_name')->get(),
        ];
    }
}
