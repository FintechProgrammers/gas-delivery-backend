<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'amount' => $this->amount,
            'currency' => "NGN",
            'type' => $this->type,
            'action' => $this->action,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
