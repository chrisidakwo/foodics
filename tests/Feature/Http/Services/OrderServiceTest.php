<?php

namespace Tests\Feature\Http\Services;

use App\Events\OrderCreated;
use App\Http\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_it_correctly_orders_for_products(): void
    {
        Event::fake();

        /** @var OrderService $service */
        $service = resolve(OrderService::class);
        $order = $service->order([
            [
                'product_id' => 1,
                'quantity' => 2,
            ],
            [
                'product_id' => 2,
                'quantity' => 1,
            ]
        ]);

        $this->assertDatabaseCount('orders', 1);

        $this->assertCount(2, $order->products);

        $order->products->each(function ($product) {
            $this->assertContains($product->id, [1, 2]);
        });

        Event::assertDispatchedTimes(OrderCreated::class);
    }
}
