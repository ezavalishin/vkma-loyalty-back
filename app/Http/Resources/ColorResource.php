<?php

namespace App\Http\Resources;

use App\Color;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ColorResource
 * @package App\Http\Resources
 * @mixin Color
 */
class ColorResource extends JsonResource {
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
        ];
    }
}
