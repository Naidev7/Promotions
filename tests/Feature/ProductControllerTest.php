<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Ejecutar migraciones y seeders
        $this->artisan('migrate');
        $this->seed('ProductSeeder');
    }

    /** @test */
    public function it_returns_all_products_with_discounts_applied()
    {
        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'products')
            ->assertJsonFragment([
                'sku' => '000001',
                'price' => [
                    'original' => 89000,
                    'final' => 62300,
                    'discount_percentage' => '30%',
                    'currency' => 'EUR',
                ],
            ])
            ->assertJsonFragment([
                'sku' => '000003',
                'price' => [
                    'original' => 71000,
                    'final' => 49700, // 71000 * 0.7 = 49700
                    'discount_percentage' => '30%',
                ],
            ]);
    }

    /** @test */
    public function it_applies_the_highest_discount_when_multiple_discounts_apply()
    {
        // El SKU '000003' tiene dos descuentos: 30% por categoría y 15% por SKU
        // Debe aplicarse el 30%

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'sku' => '000003',
                'price' => [
                    'original' => 71000,
                    'final' => 49700, // Aplicando 30%
                    'discount_percentage' => '30%',
                ],
            ]);
    }

    /** @test */
    public function it_filters_products_by_category()
    {
        $response = $this->getJson('/api/products?category=sandals');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'products')
            ->assertJsonFragment([
                'sku' => '000004',
                'category' => 'sandals',
            ]);
    }

    /** @test */
    public function it_filters_products_by_price_less_than_before_discounts()
    {
        // Filtrar productos con precio <= 80000
        $response = $this->getJson('/api/products?priceLessThan=80000');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'products') // SKUs 000003, 000004, 000005
            ->assertJsonFragment(['sku' => '000003'])
            ->assertJsonFragment(['sku' => '000004'])
            ->assertJsonFragment(['sku' => '000005']);
    }

    /** @test */
    public function it_limits_the_response_to_five_products()
    {
        // Insertar más productos para probar el límite
        Product::factory()->count(10)->create([
            'category' => 'boots',
            'price' => 100000,
        ]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'products');
    }
}
