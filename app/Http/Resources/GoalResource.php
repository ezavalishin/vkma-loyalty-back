<?php

namespace App\Http\Resources;

use App\Color;
use App\Goal;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'checkins_count' => $this->checkins_count
        ];
    }
}
