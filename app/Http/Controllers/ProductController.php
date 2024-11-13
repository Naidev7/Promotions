<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\DiscountService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    public function index(Request $request)
    {
        // Get filtering parameters
        $category = $request->query('category');
        $priceLessThan = $request->query('priceLessThan');

        // Build the base query
        $query = Product::query();

        // Apply category filter if it exists
        if ($category) {
            $query->where('category', $category);
        }

        // Apply price filter before discounts if it exists
        if ($priceLessThan !== null) {
            $query->where('price', '<=', (int) $priceLessThan);
        }

        // Limit to 5 products
        $products = $query->limit(5)->get();

        // Apply discounts using the service
        $products = $products->map(function ($product) {
            $productArray = $product->toArray();
            $maxDiscount = $this->discountService->calculateMaxDiscount($productArray);

            if ($maxDiscount !== null) {
                $original = $productArray['price'];
                $final = $this->discountService->applyDiscount($original, $maxDiscount);
                $discountPercentage = "{$maxDiscount}%";
            } else {
                $original = $productArray['price'];
                $final = $original;
                $discountPercentage = null;
            }

            return [
                'sku' => $productArray['sku'],
                'name' => $productArray['name'],
                'category' => $productArray['category'],
                'price' => [
                    'original' => $original,
                    'final' => $final,
                    'discount_percentage' => $discountPercentage,
                    'currency' => 'EUR',
                ],
            ];
        });

        // Return the response in JSON format
        return response()->json(['products' => $products]);
    }
}
