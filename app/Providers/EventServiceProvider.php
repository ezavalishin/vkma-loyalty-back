<?php

namespace App\Providers;

use App\Events\UserCreated;
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
        ]
    ];
}