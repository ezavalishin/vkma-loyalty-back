<?php

namespace App\Listeners;

use App\Events\CardCreated;

class FillCardCheckins
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param CardCreated $event
     * @return void
     */
    public function handle(CardCreated $event)
    {
        $card = $event->card;

        for ($i = 0; $i < $card->goal->checkins_count; $i++) {
            $card->checkins()->create([]);
        }
    }
}
