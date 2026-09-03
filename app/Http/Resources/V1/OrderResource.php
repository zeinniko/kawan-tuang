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
            'status' => $this->status,
            'payment_status' => $this->payment->status,
            'shipping_status' => $this->delivery->status,
            'total_items_price' => (float) $this->total_items_price,
            'subtotal' => (float) $this->subtotal,
            'discount_amount' => (float) $this->discount_amount,
            'delivery_fee' => (float) $this->delivery_fee,
            'admin_fee' => (float) $this->admin_fee,
            'total_amount' => (float) $this->total_amount,
            'courier_company' => $this->courier_company,
            'courier_type' => $this->courier_type,
            'waybill_number' => $this->waybill_number,
            'store' => new StoreResource($this->whenLoaded('store')),
            'address' => $this->address_snapshot,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}