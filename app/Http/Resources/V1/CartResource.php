<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $items = $this->items ?? collect();
        
        $totalItems = $items->sum('quantity');
        $totalPrice = $items->sum(function ($item) {
            $price = $item->unit_price ?? $item->product?->price ?? 0;
            return $item->quantity * $price;
        });

        return [
            'id' => $this->id,
            'store_id' => $this->store_id ?? null,
            'total_items' => (int) $totalItems,
            'total_price' => (float) $totalPrice,
            'items' => CartItemResource::collection($items),
        ];
    }
}