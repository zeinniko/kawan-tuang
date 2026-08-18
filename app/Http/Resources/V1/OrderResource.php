<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'status' => $this->status, // pending, paid, processing, shipping, completed, cancelled
            'payment_status' => $this->payment_status, // unpaid, paid, expired, refunded
            'shipping_status' => $this->shipping_status,
            'total_items_price' => (float) $this->total_items_price,
            'shipping_cost' => (float) $this->shipping_cost,
            'discount_amount' => (float) $this->discount_amount,
            'grand_total' => (float) $this->grand_total,
            'courier_company' => $this->courier_company,
            'courier_type' => $this->courier_type,
            'waybill_number' => $this->waybill_number,
            'store' => new StoreResource($this->whenLoaded('store')),
            'address' => new UserAddressResource($this->whenLoaded('address')),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}