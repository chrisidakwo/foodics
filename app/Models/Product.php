<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property string $name
 * @property-read Collection|Order[] $orders
 * @property-read Collection|Ingredient[] $productIngredients
 */
class Product extends Model
{
    protected $fillable = ['name'];

    /**
     * @return BelongsToMany
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'product_orders')
            ->withPivot(['quantity']);
    }

    /**
     * @return BelongsToMany
     */
    public function productIngredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'product_ingredients')
            ->withPivot(['ingredient_quantity']);
    }
}
