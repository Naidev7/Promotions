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


        $products = Product::category($category)
            ->priceLessThan($priceLessThan)
            ->limit(5)
            ->get();


        // Map & transform the products
        $products = $products->map(function ($product) {
            $maxDiscount = $product->calculateMaxDiscount();

            if ($maxDiscount !== null) {
                $original = $product->price;
                $final = $product->applyDiscount($maxDiscount);
                $discountPercentage = "{$maxDiscount}%";
            } else {
                $original = $product->price;
                $final = $original;
                $discountPercentage = null;
            }

            return [
                'sku' => $product->sku,
                'name' => $product->name,
                'category' => $product->category,
                'price' => [
                    'original' => $original,
                    'final' => $final,
                    'discount_percentage' => $discountPercentage,
                    'currency' => 'EUR',
                ],
            ];
        });

        return response()->json(['products' => $products]);
    }
}
