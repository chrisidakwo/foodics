<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class IngredientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = [
            'beef' => 20,
            'cheese' => 5,
            'onion' => 1,
        ];

        foreach ($ingredients as $ingredient => $quantity) {
            /** @var Ingredient $ingredient */
            $ingredient = Ingredient::query()->create([
                'name' => Str::title($ingredient),
                'quantity' => $quantity * 1000,
            ]);

            $ingredient->updateThresholdQuantity();
        }
    }
}
