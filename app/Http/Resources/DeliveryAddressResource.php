<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'  => $this->uuid,
            'country' => new CountryResource($this->country),
            'state' => $this->state,
            'city' => $this->city,
            'house_number'  => $this->house_number,
            'street' => $this->street,
            'land_mark' => $this->nearest_land_mark,
            'created_at' => $this->created_at,
        ];
    }
}
