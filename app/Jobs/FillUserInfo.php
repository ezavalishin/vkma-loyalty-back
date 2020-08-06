<?php

namespace App\Jobs;

use App\Jobs\Traits\VkApiable;
use App\Services\VkClient;
use App\User;
use Illuminate\Contracts\Redis\LimiterTimeoutException;

class FillUserInfo extends Job
{
    use VkApiable;

    protected User $user;

    public bool $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws LimiterTimeoutException
     */
    public function handle()
    {
        $this->throttle(function () {
            $user = $this->user;

            $data = (new VkClient())->getUsers($user->vk_user_id, [
                'first_name',
                'last_name',
                'photo_200',
            ]);


            $user->first_name = $data['first_name'] ?? null;
            $user->last_name = $data['last_name'] ?? null;
            $user->avatar = $data['photo_200'] ?? null;

            $user->save();
        });
    }
}
