<?php

namespace Tests\Feature\Listeners;

use App\Events\OrderCreated;
use App\Listeners\HandleOrder;
use App\Models\Ingredient;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class HandleOrderTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_it_correctly_decrements_ingredient_quantity(): void
    {
        /** @var Order $order */
        $order = Order::factory()->createQuietly();
        $order->products()->attach(1, [
            'quantity' => 3,
        ]);

        $order = $order->load(['products', 'products.productIngredients']);

        $productsIngredients = $this->getProductsIngredients($order)->toArray();

        /** @var HandleOrder $listener */
        $listener = resolve(HandleOrder::class);
        $listener->handle(new OrderCreated($order));

        $this->getProductsIngredients($order)->each(function ($ingredient) use ($productsIngredients) {
            $item = array_values(
                array_filter($productsIngredients, fn ($item) => $item['id'] === $ingredient->id)
            )[0];

            $this->assertEquals(
                $ingredient->quantity,
                (
                    $item['quantity'] - ($item['pivot']['ingredient_quantity'] * 3)
                )
            );
        });
    }

    /**
     * @param Order $order
     *
     * @return Collection<Ingredient>
     */
    private function getProductsIngredients(Order $order): Collection
    {
        $ingredients = collect([]);
        $productsIngredients = $order->products->map->productIngredients;

        foreach ($productsIngredients as $productsIngredient) {
            $ingredients->push(...$productsIngredient);
        }

        return $ingredients;
    }
}
