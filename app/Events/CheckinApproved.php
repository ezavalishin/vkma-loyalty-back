<?php

namespace App\Events;

use App\Checkin;

class CheckinApproved extends Event
{
    public Checkin $checkin;

    /**
     * Create a new event instance.
     *
     * @param Checkin $checkin
     */
    public function __construct(Checkin $checkin)
    {
        $this->checkin = $checkin;
    }
}
