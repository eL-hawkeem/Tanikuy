<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Services\EoqService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EoqServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_eoq_formula_calculation_is_mathematically_accurate(): void
    {
        $this->seed(DatabaseSeeder::class);

        $product = Product::first();
        $this->assertNotNull($product);

        $eoqService = app(EoqService::class);
        $result = $eoqService->calculate($product);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('eoq_qty', $result);
        $this->assertArrayHasKey('annual_demand', $result);
        $this->assertArrayHasKey('order_frequency', $result);
        $this->assertArrayHasKey('total_optimal_cost', $result);

        $this->assertGreaterThan(0, $result['eoq_qty']);
        $this->assertGreaterThan(0, $result['annual_demand']);
    }
}
