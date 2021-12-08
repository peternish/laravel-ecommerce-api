<?php

namespace App\Traits;

use App\Models\Product;

trait CalculateOrderValue
{
    /**
     * Calculate order value.
     *
     * @return integer
     */
    private function calculateOrderValue(array $products): int
    {
        $value = 0;
        foreach ($products as $productId){
            $product = Product::FindOrFail($productId);
            $value += $product->price;
        }
        return $value;
    }
}
