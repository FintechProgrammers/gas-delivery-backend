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

        // $pricing = $this->pricing->map(fn ($price) => ['id' => $price->uuid, 'price' => $price->price, 'formatted_price' => number_format($price->price, 2, '.', ',') . ' NGN', 'kg' => $price->kg]);

        $price = $this->pricing ? ['id' => $this->pricing->uuid, 'price' => $this->pricing->price, 'formatted_price' => number_format($this->pricing->price, 2, '.', ',') . ' NGN', 'kg' => $this->pricing->kg] : null;

        return [
            'id' => $this->uuid,
            'business_name' => $this->business_name,
            'profile_photo' => $this->profile_picture,
            'address' => $this->profile->address,
            'opening_days' => $this->profile->opening_days,
            'opening_hours' => $this->profile->opening_hours,
            'pricing' => $price
        ];
    }
}
