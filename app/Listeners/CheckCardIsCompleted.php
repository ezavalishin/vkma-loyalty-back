<?php

namespace App\Listeners;

use App\Events\CheckinApproved;

class CheckCardIsCompleted
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
     * @param \App\Events\ExampleEvent $event
     * @return void
     */
    public function handle(CheckinApproved $event)
    {
        $checkin = $event->checkin;
        $card = $checkin->card;

        if ($card->checkins()->whereNull('approved_at')->count() === 0) {
            $card->recreate();
        }
    }
}
