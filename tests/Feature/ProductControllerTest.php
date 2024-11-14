<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_all_products_with_discounts_applied()
    {
        Product::create([
            'sku' => '000001',
            'name' => 'BV Lean leather ankle boots',
            'category' => 'boots',
            'price' => 89000,
        ]);

        Product::create([
            'sku' => '000002',
            'name' => 'BV Lean leather ankle boots',
            'category' => 'boots',
            'price' => 99000,
        ]);

        Product::create([
            'sku' => '000003',
            'name' => 'Ashlington leather ankle boots',
            'category' => 'boots',
            'price' => 71000,
        ]);

        Product::create([
            'sku' => '000004',
            'name' => 'Naima embellished suede sandals',
            'category' => 'sandals',
            'price' => 79500,
        ]);

        Product::create([
            'sku' => '000005',
            'name' => 'Nathane leather sneakers',
            'category' => 'sneakers',
            'price' => 59000,
        ]);

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
                    'final' => 49700,
                    'discount_percentage' => '30%',
                    'currency' => 'EUR',
                ],
            ]);
    }

    /** @test */
    public function it_applies_the_highest_discount_when_multiple_discounts_apply()
    {
        Product::create([
            'sku' => '000003',
            'name' => 'Ashlington leather ankle boots',
            'category' => 'boots',
            'price' => 71000,
        ]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'sku' => '000003',
                'price' => [
                    'original' => 71000,
                    'final' => 49700,
                    'discount_percentage' => '30%',
                    'currency' => 'EUR',
                ],
            ]);
    }

    /** @test */
    public function it_filters_products_by_category()
    {
        Product::create([
            'sku' => '000004',
            'name' => 'Naima embellished suede sandals',
            'category' => 'sandals',
            'price' => 79500,
        ]);

        Product::factory()->count(3)->create(['category' => 'boots']);

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
        Product::create([
            'sku' => '000003',
            'name' => 'Ashlington leather ankle boots',
            'category' => 'boots',
            'price' => 71000,
        ]);

        Product::create([
            'sku' => '000004',
            'name' => 'Naima embellished suede sandals',
            'category' => 'sandals',
            'price' => 79500,
        ]);

        Product::create([
            'sku' => '000005',
            'name' => 'Nathane leather sneakers',
            'category' => 'sneakers',
            'price' => 59000,
        ]);

        Product::create([
            'sku' => '000006',
            'name' => 'Expensive boots',
            'category' => 'boots',
            'price' => 150000,
        ]);

        $response = $this->getJson('/api/products?priceLessThan=80000');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'products')
            ->assertJsonFragment(['sku' => '000003'])
            ->assertJsonFragment(['sku' => '000004'])
            ->assertJsonFragment(['sku' => '000005'])
            ->assertJsonMissing(['sku' => '000006']);
    }

    /** @test */
    public function it_limits_the_response_to_five_products()
    {
        Product::factory()->count(10)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'products');
    }
}
