<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingTrackingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'waybill_number' => $this['waybill_number'] ?? null,
            'status' => $this['status'] ?? 'pending',
            'courier' => [
                'company' => $this['courier']['company'] ?? null,
                'name' => $this['courier']['name'] ?? null,
                'driver_name' => $this['courier']['driver_name'] ?? null,
                'driver_phone' => $this['courier']['driver_phone'] ?? null,
            ],
            'tracking_url' => $this['link'] ?? null,
            'history' => collect($this['history'] ?? [])->map(fn ($item) => [
                'note' => $item['note'],
                'updated_at' => $item['updated_at'],
                'status' => $item['status'],
            ]),
        ];
    }
}