<?php

namespace App\Jobs;

use App\City;
use App\Group;
use App\Jobs\Traits\VkApiable;
use App\Services\VkClient;
use App\User;
use Illuminate\Contracts\Redis\LimiterTimeoutException;

class FillGroupInfo extends Job
{
    use VkApiable;

    protected Group $group;

    public bool $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     *
     * @param Group $group
     */
    public function __construct(Group $group)
    {
        $this->group = $group;
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
            $group = $this->group;

            $data = (new VkClient())->getGroupById($group->vk_group_id, [
                'name',
                'photo_200',
                'city'
            ]);


            $group->title = $data['name'] ?? null;
            $group->avatar = $data['photo_200'] ?? null;


            if (isset($data['city'])) {
                $city = City::query()->firstOrCreate([
                    'id' => $data['city']['id']
                ], [
                    'title' => $data['city']['title']
                ]);

                $group->city_id = $city->id;
            }

            $group->save();
        });
    }
}
