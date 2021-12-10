<?php

namespace Tests\Feature;

use App\Helpers\Helper;
use App\Models\Product;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class PublicProductsTest extends TestCase
{
    /**
     * Get products.
     *
     * @group public_products
     * @return void
     */
    public function testGetProducts()
    {
        $response = $this->get('/public/products');

        $productsCount = Product::all()->count();

        $response
        ->assertOk()
        ->assertJson(function (AssertableJson $json) use($productsCount) {
            $json->has($productsCount);
        });
    }

    /**
     * Get product.
     *
     * @group public_products
     * @return void
     */
    public function testProduct()
    {
        $product = Product::all()->random();

        $response = $this->get('/public/products/'.$product->id);

        $response
        ->assertOk()
        ->assertJson([
            'id' => $product->id,
            'price' => Helper::convertToPLN($product->price)
        ]);
    }

    /**
     * Get product not found.
     *
     * @group public_products
     * @return void
     */
    public function testProductNotFound()
    {
        $response = $this->get('/public/products/0');

        $response->assertStatus(404);
    }
}
