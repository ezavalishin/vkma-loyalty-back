<?php

namespace App\Events;

use App\Card;

class CardCreated extends Event
{
    public Card $card;

    /**
     * Create a new event instance.
     *
     * @param Card $card
     */
    public function __construct(Card $card)
    {
        $this->card = $card;
    }
}
