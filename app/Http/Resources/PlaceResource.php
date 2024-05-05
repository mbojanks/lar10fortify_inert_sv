<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlaceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "placeid" => $this->placeid,
            "place" => $this->place,
            "placepostnum" => $this->placepostnum,
            "municipality" => $this->municipality->municipality,
            "country" => $this->municipality->country->country
        ];
    }
}
