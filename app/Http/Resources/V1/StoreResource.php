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
            'open_time' => $this->open_time,
            'close_time' => $this->close_time,
            'is_active' => (bool) $this->is_active,
            // Jarak dalam kilometer (hanya tampil jika dipanggil via endpoint nearest)
            'distance_km' => isset($this->distance) ? round((float) $this->distance, 2) : null,
            'distance' => $this->when(isset($this->distance), (float) $this->distance),
            'distance_text' => $this->when(isset($this->distance_text), $this->distance_text),
            'distance_source' => $this->when(isset($this->distance_source), $this->distance_source),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}