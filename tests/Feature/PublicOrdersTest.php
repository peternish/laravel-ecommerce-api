<?php

namespace Tests\Feature;

use App\Helpers\Helper;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PublicOrdersTest extends TestCase
{
    /**
     * Create order as guest.
     *
     * @group public_orders
     * @return void
     */
    public function testCreateOrderAsGuest()
    {
        $orderData = $this->getOrder();
        $faker = $this->getFaker();
        $email = $faker->unique()->safeEmail();

        $response = $this->postJson('/public/orders', [
            'email' => $email,
            'products' => $orderData['products']
        ]);

        $response
        ->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'order' => [
                    'value' => Helper::convertToPLN($orderData['value'])
                ],
                'user' => [
                    'email' => $email
                ],
            ]
        ]);

        $user = User::where('email', '=', $email)->first();
        $this->assertNotEmpty($user);
        $this->assertEquals($email, $user->email);

        $order = Order::all()->last();
        $this->assertNotEmpty($order);
        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals($orderData['value'], $order->value);
    }

    /**
     * Create order as guest without email.
     *
     * @group public_orders
     * @return void
     */
    public function testCreateOrderAsGuestWithoutEmail()
    {
        $orderData = $this->getOrder();
        $faker = $this->getFaker();

        $response = $this->postJson('/public/orders', [
            'products' => $orderData['products']
        ]);

        $response->assertStatus(422);
    }

    /**
     * Create order as user.
     *
     * @group public_orders
     * @return void
     */
    public function testCreateOrderAsUser()
    {
        $orderData = $this->getOrder();
        $user = $this->getRandomUser();

        Sanctum::actingAs($user);

        $response = $this->postJson('/public/orders', [
            'products' => $orderData['products']
        ]);

        $response
        ->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'order' => [
                    'value' => Helper::convertToPLN($orderData['value'])
                ]
            ]
        ]);

        $order = Order::all()->last();
        $this->assertNotEmpty($order);
        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals($orderData['value'], $order->value);
    }

    /**
     * Create order without products.
     *
     * @group public_orders
     * @return void
     */
    public function testCreateOrderWithoutProducts()
    {
        $user = $this->getRandomUser();

        Sanctum::actingAs($user);

        $response = $this->postJson('/public/orders', [
            'products' => []
        ]);

        $response->assertStatus(422);
    }

    private function getOrder(): array
    {
        $value = 0;
        $ids = [];

        $products = Product::all()->random(3);
        foreach ($products as $product){
            $value += $product->price;
            $ids[] = $product->id;
        }

        return [
            'value' => $value,
            'products' => $ids
        ];
    }
}
