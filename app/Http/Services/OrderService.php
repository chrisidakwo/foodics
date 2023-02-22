<?php

namespace App\Http\Services;

use App\Events\OrderCreated;
use App\Models\Order;
use App\Models\Product;

class OrderService
{
    /**
     * @param array $products
     *
     * @return Order
     */
    public function order(array $products): Order
    {
        /** @var Order $order */
        $order = Order::query()->create();

        foreach ($products as $product) {
            $productId = $product['product_id'];
            $quantity = $product['quantity'];

            /** @var Product $product */
            $product = Product::query()
                ->find($productId)
                ->first();

            $product->orders()->attach($order->id, [
                'quantity' => $quantity,
            ]);
        }

        event(new OrderCreated($order->load(['products', 'products.productIngredients'])));

        return $order;
    }
}
