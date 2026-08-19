<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\ProductionBundle;
use App\Models\SewingLine;
use App\Models\Style;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductionBundleController extends Controller
{
    public function index(): View
    {
        $productionBundles = ProductionBundle::query()
            ->with(['buyer', 'style', 'sewingLine'])
            ->latest('production_date')
            ->get();

        return view('production-bundles.index', compact('productionBundles'));
    }

    public function create(): View
    {
        return view('production-bundles.create', $this->formOptions());
    }

    public function store(Request $request): RedirectResponse
    {
        $productionBundle = ProductionBundle::query()->create($this->validatedData($request));

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

    public function update(Request $request, ProductionBundle $productionBundle): RedirectResponse
    {
        $productionBundle->update($this->validatedData($request, $productionBundle));

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
    private function formOptions(): array
    {
        return [
            'buyers' => Buyer::query()->orderBy('buyer_name')->get(),
            'styles' => Style::query()->with('buyer')->orderBy('style_no')->get(),
            'sewingLines' => SewingLine::query()->orderBy('line_name')->get(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?ProductionBundle $productionBundle = null): array
    {
        return $request->validate([
            'bundle_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('production_bundles', 'bundle_no')->ignore($productionBundle),
            ],
            'buyer_id' => ['required', 'exists:buyers,id'],
            'style_id' => ['required', 'exists:styles,id'],
            'color' => ['required', 'string', 'max:100'],
            'size' => ['required', 'string', 'max:50'],
            'line_id' => ['required', 'exists:sewing_lines,id'],
            'quantity' => ['required', 'integer', 'min:0'],
            'completed_qty' => ['required', 'integer', 'min:0'],
            'rejected_qty' => ['required', 'integer', 'min:0'],
            'operator_name' => ['nullable', 'string', 'max:150'],
            'production_date' => ['required', 'date'],
            'remarks' => ['nullable', 'string'],
        ]);
    }
}
