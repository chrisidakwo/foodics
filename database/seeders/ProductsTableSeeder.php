<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use Exception;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws Exception
     */
    public function run(): void
    {
        $ingredient = Ingredient::query()->latest()->first();

        if ($ingredient === null) {
            throw new Exception("You'd probably want to have your ingredients before cooking?");
        }

        $products = [
            [
                'name' => 'Burger',
                'ingredients' => [
                    'Beef' => 150,
                    'Cheese' => 30,
                    'Onion' => 20
                ]
            ],

            [
                'name' => 'Sandwich',
                'ingredients' => [
                    'Beef' => 120,
                    'Cheese' => 18,
                    'Onion' => 10,
                ]
            ]
        ];

        foreach ($products as $productArr) {
            $product = Product::query()->create([
                'name' => $productArr['name'],
            ]);

            $ingredients = $productArr['ingredients'];

            foreach ($ingredients as $ingredient => $quantity) {
                $ingredient = Ingredient::query()->where('name',  $ingredient)->first();

                $product->productIngredients()->attach($ingredient->id,  [
                    'ingredient_quantity' => $quantity,
                ]);
            }
        }
    }
}
