<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'address' => $this->address,
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'phone_number' => $this->phone_number,
            'operating_hours' => $this->operating_hours,
            'is_active' => (bool) $this->is_active,
            // Jarak dalam kilometer (hanya tampil jika dipanggil via endpoint nearest)
            'distance_km' => isset($this->distance) ? round((float) $this->distance, 2) : null,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}