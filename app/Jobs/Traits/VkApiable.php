<?php

namespace App\Jobs\Traits;

use Illuminate\Contracts\Redis\LimiterTimeoutException;
use Illuminate\Support\Facades\Redis;

trait VkApiable
{
    public int $tries = 25;
    public int $maxExceptions = 3;

    /**
     * @param callable $closure
     * @throws LimiterTimeoutException
     */
    protected function throttle(callable $closure): void
    {
        Redis::throttle('vk_api')->allow(15)
            ->every(1)
            ->then($closure, function () {
                return $this->release(60);
            });
    }
}
