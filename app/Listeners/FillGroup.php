<?php

namespace App\Listeners;

use App\Events\ExampleEvent;
use App\Events\GroupCreated;
use App\Events\UserCreated;
use App\Jobs\FillGroupInfo;
use App\Jobs\FillUserInfo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
        dispatch(new FillGroupInfo($event->group));
    }
}
