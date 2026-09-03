<?php

namespace Tests\Feature;

use App\Models\Buyer;
use App\Models\ProductionBundle;
use App\Models\SewingLine;
use App\Models\Style;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class ProductionBundleApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(['role' => 'admin']), 'sanctum');
    }

    public function test_index_returns_paginated_json_with_relations_and_calculations(): void
    {
        $this->createBundle([
            'bundle_no' => 'BND-001',
            'quantity' => 100,
            'completed_qty' => 80,
            'rejected_qty' => 10,
        ]);

        $response = $this->getJson('/api/bundles');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('meta.per_page', 20)
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.bundle_no', 'BND-001')
            ->assertJsonPath('data.0.balance', 10)
            ->assertJsonPath('data.0.efficiency_percent', 80)
            ->assertJsonPath('data.0.rejection_percent', 10)
            ->assertJsonPath('data.0.buyer.buyer_name', 'Test Buyer')
            ->assertJsonPath('data.0.style.style_no', 'TEST-STYLE-001')
            ->assertJsonPath('data.0.sewing_line.line_name', 'Test Line');
    }

    public function test_index_supports_allowed_page_sizes_and_defaults_invalid_values(): void
    {
        for ($i = 1; $i <= 21; $i++) {
            $this->createBundle(['bundle_no' => sprintf('BND-%03d', $i)]);
        }

        $this->getJson('/api/bundles')
            ->assertOk()
            ->assertJsonPath('meta.per_page', 20)
            ->assertJsonCount(20, 'data');

        $this->getJson('/api/bundles?per_page=50')
            ->assertOk()
            ->assertJsonPath('meta.per_page', 50)
            ->assertJsonCount(21, 'data');

        $this->getJson('/api/bundles?per_page=30')
            ->assertOk()
            ->assertJsonPath('meta.per_page', 20)
            ->assertJsonCount(20, 'data');
    }

    public function test_store_creates_a_production_bundle(): void
    {
        $response = $this->postJson('/api/bundles', $this->validData());

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.bundle_no', 'BND-001');

        $this->assertDatabaseHas('production_bundles', ['bundle_no' => 'BND-001']);
    }

    public function test_store_returns_validation_errors_as_json(): void
    {
        $this->postJson('/api/bundles', [
            'bundle_no' => '',
            'quantity' => 100,
            'completed_qty' => 80,
            'rejected_qty' => 30,
        ])->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonValidationErrors(['bundle_no', 'completed_qty']);
    }

    public function test_update_allows_its_own_bundle_number_and_rejects_duplicates(): void
    {
        $bundle = $this->createBundle(['bundle_no' => 'BND-001']);
        $this->createBundle(['bundle_no' => 'BND-002']);

        $this->putJson('/api/bundles/'.$bundle->id, $this->validData([
            'bundle_no' => 'BND-001',
            'color' => 'Navy Blue',
        ]))->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.color', 'Navy Blue');

        $this->putJson('/api/bundles/'.$bundle->id, $this->validData([
            'bundle_no' => 'BND-002',
        ]))->assertStatus(422)
            ->assertJsonValidationErrors('bundle_no');
    }

    public function test_destroy_soft_deletes_the_bundle(): void
    {
        $bundle = $this->createBundle();

        $this->deleteJson('/api/bundles/'.$bundle->id)
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted($bundle);

        $this->deleteJson('/api/bundles/'.$bundle->id)
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }

    public function test_dashboard_returns_aggregate_metrics(): void
    {
        $this->createBundle([
            'bundle_no' => 'BND-TODAY',
            'quantity' => 100,
            'completed_qty' => 80,
            'rejected_qty' => 10,
            'production_date' => now()->toDateString(),
        ]);
        $this->createBundle([
            'bundle_no' => 'BND-YESTERDAY',
            'quantity' => 50,
            'completed_qty' => 40,
            'rejected_qty' => 5,
            'production_date' => now()->subDay()->toDateString(),
        ]);

        $this->getJson('/api/dashboard')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.total_bundles', 2)
            ->assertJsonPath('data.total_quantity', 150)
            ->assertJsonPath('data.total_completed', 120)
            ->assertJsonPath('data.total_rejected', 15)
            ->assertJsonPath('data.average_efficiency', 80)
            ->assertJsonPath('data.today_production', 100)
            ->assertJsonPath('data.today_rejection', 10);
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function createBundle(array $overrides = []): ProductionBundle
    {
        return ProductionBundle::query()->create($this->validData($overrides));
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
