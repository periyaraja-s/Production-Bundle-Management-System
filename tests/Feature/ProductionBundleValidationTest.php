<?php

namespace Tests\Feature;

use App\Models\Buyer;
use App\Models\ProductionBundle;
use App\Models\SewingLine;
use App\Models\Style;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionBundleValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_rejects_invalid_bundle_data(): void
    {
        $this->post(route('production-bundles.store'), [
            'bundle_no' => '',
            'buyer_id' => 999,
            'style_id' => 999,
            'line_id' => 999,
            'color' => str_repeat('C', 101),
            'size' => str_repeat('S', 51),
            'quantity' => 0,
            'completed_qty' => 1,
            'rejected_qty' => 1,
            'operator_name' => str_repeat('O', 151),
            'production_date' => now()->addDay()->toDateString(),
        ])->assertSessionHasErrors([
            'bundle_no',
            'buyer_id',
            'style_id',
            'line_id',
            'color',
            'size',
            'quantity',
            'completed_qty',
            'rejected_qty',
            'operator_name',
            'production_date',
        ]);
    }

    public function test_store_rejects_completed_and_rejected_quantities_above_quantity(): void
    {
        $this->post(route('production-bundles.store'), $this->validData([
            'quantity' => 100,
            'completed_qty' => 80,
            'rejected_qty' => 30,
        ]))->assertSessionHasErrors('completed_qty');
    }

    public function test_bundle_number_must_be_unique_even_after_soft_deletion(): void
    {
        $bundle = ProductionBundle::query()->create($this->validData());
        $bundle->delete();

        $this->post(route('production-bundles.store'), $this->validData())
            ->assertSessionHasErrors('bundle_no');
    }

    public function test_update_allows_its_own_bundle_number_but_rejects_another_bundles_number(): void
    {
        $bundle = ProductionBundle::query()->create($this->validData());
        $otherBundle = ProductionBundle::query()->create($this->validData(['bundle_no' => 'BND-002']));

        $this->put(route('production-bundles.update', $bundle), $this->validData(['color' => 'Navy Blue']))
            ->assertSessionDoesntHaveErrors();

        $this->put(route('production-bundles.update', $bundle), $this->validData(['bundle_no' => $otherBundle->bundle_no]))
            ->assertSessionHasErrors('bundle_no');
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validData(array $overrides = []): array
    {
        $buyer = Buyer::query()->firstOrCreate(['buyer_name' => 'Test Buyer']);
        $style = Style::query()->firstOrCreate(
            ['buyer_id' => $buyer->id, 'style_no' => 'TEST-STYLE-001'],
        );
        $line = SewingLine::query()->firstOrCreate(['line_name' => 'Test Line']);

        return array_merge([
            'bundle_no' => 'BND-001',
            'buyer_id' => $buyer->id,
            'style_id' => $style->id,
            'color' => 'Black',
            'size' => 'M',
            'line_id' => $line->id,
            'quantity' => 100,
            'completed_qty' => 80,
            'rejected_qty' => 10,
            'operator_name' => 'Test Operator',
            'production_date' => now()->toDateString(),
            'remarks' => 'Test remarks',
        ], $overrides);
    }
}
