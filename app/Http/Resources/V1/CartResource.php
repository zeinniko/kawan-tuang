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
        $totalPrice = $items->sum(fn ($item) => $item->quantity * $item->unit_price);

        return [
            'id' => $this->id,
            'store_id' => $this->store_id,
            'total_items' => (int) $totalItems,
            'total_price' => (float) $totalPrice,
            'items' => CartItemResource::collection($items),
        ];
    }
}