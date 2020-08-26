<?php

namespace App\Http\Resources;

use App\Color;
use App\Goal;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

/**
 * Class GoalResource
 * @package App\Http\Resources
 * @mixin Goal
 */
class GoalResource extends JsonResource {
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'checkins_count' => $this->checkins_count,
            'group' => new GroupResource($this->whenLoaded('group')),
            'color' => new ColorResource($this->whenLoaded('color')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'is_attached' => $this->getAttribute('is_attached') ?? false
        ];
    }
}
