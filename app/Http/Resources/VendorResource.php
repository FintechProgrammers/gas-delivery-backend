<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'business_name' => $this->business_name,
            'profile_photo' => $this->profile_picture,
            'address' => $this->profile->address,
            'opening_days' => $this->profile->opening_days,
            'opening_hours' => $this->profile->opening_hours,
            'is_available'         => (bool) $this->is_available,
            'price_per_kg'         => optional($this->pricePerKg)->price,
        ];
    }
}
