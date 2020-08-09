<?php

namespace App\Http\Resources;

use App\Card;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

/**
 * Class ColorResource
 * @package App\Http\Resources
 * @mixin Card
 */
class CardResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'hash' => $this->getCheckinHash(),
            'total_count' => $this->getTotalCheckinCount(),
            'approved_count' => $this->getApprovedCheckinCount(),
            'description' => $this->getDescription(),
            'group' => new GroupResource($this->getGroup() ?? new MissingValue()),
            'color' => new ColorResource($this->getColor() ?? new MissingValue())
        ];
    }
}
