<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\Product;
use Illuminate\Support\Collection;

class HandleOrder
{
    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        foreach ($order->products as $product) {
            $this->cook($product, $product->pivot->quantity);
        }
    }

    /**
     * Cook.
     *
     * @param Product $product
     * @param int $orderQuantity
     *
     * @return Collection|array
     */
    public function cook(Product $product, int $orderQuantity): Collection|array
    {
        $productIngredients = $product->productIngredients;

        foreach ($productIngredients as $productIngredient) {
            $requestingQty = $productIngredient->pivot->ingredient_quantity * $orderQuantity;

            // To ingredients having a negative quantity
            if ($productIngredient->quantity >= $requestingQty) {
                $productIngredient->decrement('quantity', $requestingQty);
            }
        }

        return $productIngredients;
    }
}
