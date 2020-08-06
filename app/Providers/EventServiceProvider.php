<?php

namespace App\Providers;

use App\Events\CardCreated;
use App\Events\CheckinApproved;
use App\Events\GroupCreated;
use App\Events\UserCreated;
use App\Listeners\CheckCardIsCompleted;
use App\Listeners\FillCardCheckins;
use App\Listeners\FillGroup;
use App\Listeners\FillUser;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserCreated::class => [
            FillUser::class
        ],
        GroupCreated::class => [
            FillGroup::class
        ],
        CardCreated::class => [
            FillCardCheckins::class
        ],
        CheckinApproved::class => [
            CheckCardIsCompleted::class
        ]
    ];
}
