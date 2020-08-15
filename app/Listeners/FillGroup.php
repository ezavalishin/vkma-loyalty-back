<?php

namespace App\Listeners;

use App\Events\GroupCreated;
use App\Jobs\FillGroupInfo;

class FillGroup
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
     * @param GroupCreated $event
     * @return void
     */
    public function handle(GroupCreated $event)
    {
        dispatch_now(new FillGroupInfo($event->group));
    }
}
