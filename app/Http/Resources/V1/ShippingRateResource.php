<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingRateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'courier_name' => $this['courier_name'],
            'courier_code' => $this['courier_code'],
            'courier_service_code' => $this['courier_service_code'],
            'service_name' => $this['service_name'],
            'description' => $this['description'] ?? null,
            'price' => (float) $this['price'],
            'estimated_days' => $this['estimated_days'] ?? null,
            'shipment_duration_type' => $this['shipment_duration_type'] ?? null,
        ];
    }
}