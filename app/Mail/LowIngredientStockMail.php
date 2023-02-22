<?php

namespace App\Mail;

use App\Models\Ingredient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LowIngredientStockMail extends Mailable
{
    use Queueable, SerializesModels;

    public Ingredient $ingredient;

    /**
     * Create a new message instance.
     */
    public function __construct(Ingredient $ingredient)
    {
        $this->ingredient = $ingredient;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $ingredient = $this->ingredient->name;

        return new Envelope(
            subject: "Low Stock for $ingredient",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.low_ingredient_stock',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
