<?php

namespace App\Listeners;

use App\Events\ExampleEvent;
use App\Events\UserCreated;
use App\Jobs\FillUserInfo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FillUser
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
     * @param UserCreated $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        dispatch(new FillUserInfo($event->user));
    }
}
