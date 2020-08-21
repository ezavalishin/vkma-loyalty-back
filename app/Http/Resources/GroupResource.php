<?php

namespace App\Http\Resources;

use App\Category;
use App\Group;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ColorResource
 * @package App\Http\Resources
 * @mixin Group
 */
class GroupResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'vk_group_id' => $this->vk_group_id,
            'title' => $this->title,
            'avatar' => $this->avatar,
        ];
    }
}
