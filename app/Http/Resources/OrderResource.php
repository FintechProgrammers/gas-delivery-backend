<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'reference' => $this->reference,
            'gas_amount' => $this->gas_amount,
            'delivery_fee' => $this->delivery_fee,
            'price_per_km' => $this->price_per_km,
            'gas_size' => $this->gas_size,
            'cylinder_size' => $this->cylinder_size,
            'gas_amount' => $this->gas_amount,
            'total_amount' => $this->total_amount,
            'distance' => $this->distance,
            'is_paid' => (bool)$this->is_paid,
            'status' => $this->status,
            'vendor_address' => $this->to_distination,
            'user_address' => $this->from_distination,
            'business' => new VendorResource($this->business),
            'rider' => new RiderResource($this->rider),
            'customer' => new UserResource($this->user),
            'delivery_address' => new DeliveryAddressResource($this->deliveryAddress),
            'pickup_address' => [
                'longitude' => $this->deliveryAddress?->longitude,
                'latitude' => $this->deliveryAddress?->latitude,
            ],
            'station_address' => [
                'longitude' => $this->business?->profile?->longitude,
                'latitude' => $this->business?->profile?->latitude,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
