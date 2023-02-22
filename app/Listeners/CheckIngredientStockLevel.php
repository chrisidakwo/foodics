<?php

namespace App\Listeners;

use App\Events\IngredientUpdated;
use App\Mail\LowIngredientStockMail;
use Illuminate\Support\Facades\Mail;

class CheckIngredientStockLevel
{
    /**
     * Handle the event.
     */
    public function handle(IngredientUpdated $event): void
    {
        if ($event->ingredient->wasChanged('quantity')) {
            $currentQty = $event->ingredient->quantity;
            $thresholdQty = $event->ingredient->threshold_quantity;

            if ($currentQty <= $thresholdQty && null === $event->ingredient->low_stock_notification_sent_at) {
                try {
                    Mail::to('user@example.com')
                        ->send(new LowIngredientStockMail($event->ingredient));

                    // Log notification sent
                    $event->ingredient->fill([
                        'low_stock_notification_sent_at' => now()
                    ])->save();
                } catch (\Exception $ex) {}
            }
        }
    }
}
