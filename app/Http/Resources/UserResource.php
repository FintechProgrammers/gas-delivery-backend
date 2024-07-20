<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'account_type' => $this->account_type,
            'referral_code' => $this->referral_code,
            'date_of_birth' => $this->date_of_birth,
            'phone_number' => $this->phone_number,
            'profile_image' => $this->profile_picture,
            'phone_number_verified' => (bool) !empty($this->phone_number_verified_at) ? true : false,
            'email_verified'        => (bool) !empty($this->email_verified_at) ? true : false,
            'status'                => GasPriceResource::collection($this->pricing)
        ];
    }
}
