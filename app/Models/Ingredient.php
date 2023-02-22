<?php

namespace App\Models;

use App\Events\IngredientUpdated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property string $name
 * @property int $quantity
 * @property int $threshold_quantity
 * @property Carbon|null $low_stock_notification_sent_at
 * @property-read Product[]|Collection<Product> $products
 */
class Ingredient extends Model
{
    use HasFactory;

    public const STOCK_THRESHOLD = 0.5;

    protected $fillable = ['name', 'quantity', 'threshold_quantity', 'low_stock_notification_sent_at'];

    protected $dispatchesEvents = [
        'updated' => IngredientUpdated::class,
    ];

    protected $casts = [
        'low_stock_notification_sent_at' => 'datetime',
    ];

    /**
     * Update threshold quantity.
     *
     * @return void
     */
    public function updateThresholdQuantity(): void
    {
        $this->threshold_quantity = self::STOCK_THRESHOLD * $this->getAttribute('quantity');
        $this->low_stock_notification_sent_at = null;

        $this->save();
    }

    /**
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
