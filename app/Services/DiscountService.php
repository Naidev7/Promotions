<?php

namespace App\Services;

class DiscountService
{
    /**
     * Calculates the highest applicable discount for a product.
     *
     * @param array $product
     * @return int|null
     */
    public function calculateMaxDiscount(array $product): ?int
    {
        $discounts = [];

        // Discount by category
        if ($product['category'] === 'boots') {
            $discounts[] = 30;
        }

        // Discount by specific SKU
        if ($product['sku'] === '000003') {
            $discounts[] = 15;
        }

        return !empty($discounts) ? max($discounts) : null;
    }

    /**
     * Applies the discount to the original price.
     *
     * @param int $originalPrice
     * @param int $discountPercentage
     * @return int
     */
    public function applyDiscount(int $originalPrice, int $discountPercentage): int
    {
        return (int) round($originalPrice * (1 - $discountPercentage / 100));
    }
}
