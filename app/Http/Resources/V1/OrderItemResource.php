<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $subtotal = (float) ($this->subtotal_price ?? ($this->unit_price * $this->quantity));

        return [
            'id'                    => $this->id,
            'product_id'            => $this->product_id,
            'product_name'          => $this->product_name_snapshot ?? $this->product?->name,
            'product_name_snapshot' => $this->product_name_snapshot,
            'unit_price'            => (float) $this->unit_price,
            'quantity'              => (int) $this->quantity,
            'subtotal'              => $subtotal,
            'subtotal_price'        => $subtotal,
        ];
    }
}