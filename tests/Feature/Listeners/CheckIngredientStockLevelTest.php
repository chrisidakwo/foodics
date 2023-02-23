<?php

namespace Tests\Feature\Listeners;

use App\Events\IngredientUpdated;
use App\Listeners\CheckIngredientStockLevel;
use App\Mail\LowIngredientStockMail;
use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckIngredientStockLevelTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_should_not_be_sent_when_stock_higher_than_threshold(): void
    {
        $initialQty = 20000;

        /** @var Ingredient $ingredient */
        $ingredient = Ingredient::factory()->quantity($initialQty)->create();
        $ingredient->updateQuietly([
            'quantity' => $ingredient->quantity - 200,
        ]);

        Mail::fake();

        $listener = resolve(CheckIngredientStockLevel::class);
        $listener->handle(
            new IngredientUpdated($ingredient)
        );

        $this->assertLessThan($initialQty, $ingredient->quantity);
        Mail::assertNotSent(LowIngredientStockMail::class);
    }

    public function test_email_sent_on_low_stock(): void
    {
        $initialQty = 2000;

        /** @var Ingredient $ingredient */
        $ingredient = Ingredient::factory()->quantity($initialQty)->create();
        $ingredient->updateQuietly([
            'quantity' => $ingredient->quantity - 1200,
        ]);

        Mail::fake();

        $listener = resolve(CheckIngredientStockLevel::class);
        $listener->handle(
            new IngredientUpdated($ingredient)
        );

        $this->assertNotNull($ingredient->low_stock_notification_sent_at);
        $this->assertLessThan($initialQty, $ingredient->quantity);
        Mail::assertSent(function (LowIngredientStockMail $mail) use ($ingredient) {
            return $mail->ingredient->id === $ingredient->id;
        });
    }

    public function test_mail_should_not_be_sent_more_than_once(): void
    {
        $initialQty = 2000;

        /** @var Ingredient $ingredient */
        $ingredient = Ingredient::factory()->quantity($initialQty)->create();
        $ingredient->updateQuietly([
            'quantity' => $ingredient->quantity - 1200,
            'low_stock_notification_sent_at' => now(),
        ]);

        // Reduce again. Shouldn't be sent now
        $ingredient->updateQuietly([
            'quantity' => $ingredient->quantity - 100,
        ]);

        Mail::fake();

        $listener = resolve(CheckIngredientStockLevel::class);
        $listener->handle(
            new IngredientUpdated($ingredient)
        );

        Mail::assertNotSent(LowIngredientStockMail::class);
    }
}
