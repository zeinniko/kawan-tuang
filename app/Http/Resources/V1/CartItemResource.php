<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $price = (float) ($this->unit_price ?? $this->product?->price ?? 0);
        $qty = (int) $this->quantity;
        $subtotal = $price * $qty;

        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product' => new ProductResource($this->product),
            'quantity' => $qty,
            'unit_price' => $price,
            'subtotal' => $subtotal,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}