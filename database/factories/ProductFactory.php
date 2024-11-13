<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku' => $this->faker->unique()->numerify('000###'), // SKU único
            'name' => $this->faker->words(3, true), // Nombre aleatorio de tres palabras
            'category' => $this->faker->randomElement(['boots', 'sandals', 'sneakers']), // Categorías existentes
            'price' => $this->faker->numberBetween(50000, 100000), // Precio entre 50000 y 100000
        ];
    }
}
