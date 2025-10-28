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
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'address' => $this->address,
            'created_at' => $this->created_at,
        ];
    }
}
