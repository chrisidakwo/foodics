<?php

namespace Database\Factories;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ingredient>
 */
class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
        ];
    }

    /**
     * @param int $quantity Value in grams
     *
     * @return $this
     */
    public function quantity(int $quantity): static
    {
        return $this->state(function (array $attributes) use ($quantity) {
            return [
                'quantity' => $quantity,
                'threshold_quantity' => Ingredient::STOCK_THRESHOLD * $quantity,
            ];
        });
    }
}
