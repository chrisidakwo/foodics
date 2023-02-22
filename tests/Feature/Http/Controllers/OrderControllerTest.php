<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public bool $seed = true;

    public function test_it_errors_on_invalid_data(): void
    {
        $response = $this->postJson('api/orders', [
            'product_id' => 1,
            'quantity' => 2,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrorFor('products');

        $response->assertJsonPath('errors.products.0', trans('validation.required', [
            'attribute' => 'products',
        ]));

        $response = $this->postJson('api/orders', [
            'products' => [
                [
                    'product_id' => 1,
                ],
            ],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrorFor('products.0.quantity');
    }

    public function test_it_creates_a_new_order(): void
    {
        $response = $this->postJson('api/orders', [
            'products' => [
                [
                    'product_id' => 1,
                    'quantity' => 2,
                ],
            ]
        ]);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('product_orders', 1);

        $response->assertOk()
            ->assertJsonPath('id', 1)
            ->assertJsonPath('products.0.name', 'Burger');
    }
}
