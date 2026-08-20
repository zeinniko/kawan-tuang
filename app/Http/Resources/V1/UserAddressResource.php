<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'recipient_name' => $this->recipient_name,
            'recipient_phone' => $this->recipient_phone,
            'full_address' => $this->full_address,
            'notes' => $this->notes,
            'postal_code' => $this->postal_code,
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'is_primary' => (bool) $this->is_primary,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}