<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'product_sku' => $this->product_sku,
            'unit_price' => (float) $this->unit_price,
            'quantity' => (int) $this->quantity,
            'subtotal' => (float) $this->subtotal,
            'thumbnail_url' => $this->product?->thumbnail_url,
        ];
    }
}