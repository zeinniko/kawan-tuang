<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'order_number'      => $this->order_number,
            'fulfillment_type'  => $this->fulfillment_type,
            'status'            => $this->status,
            'payment_status'    => $this->payment?->payment_status,
            'shipping_status'   => $this->fulfillment_type === 'delivery' ? $this->delivery?->status : null,
            'subtotal'          => (float) $this->subtotal,
            'discount_amount'   => (float) $this->discount_amount,
            'delivery_fee'      => (float) $this->delivery_fee,
            'admin_fee'         => (float) $this->admin_fee,
            'total_amount'      => (float) $this->total_amount,
            'courier_company'   => $this->courier_company ?? $this->delivery?->courier_provider,
            'courier_type'      => $this->courier_type ?? $this->delivery?->service_type,
            'waybill_number'    => $this->waybill_number ?? $this->delivery?->waybill_number,
            'driver_name'       => $this->driver_name ?? $this->delivery?->driver_name,
            'driver_phone'      => $this->driver_phone ?? $this->delivery?->driver_phone,
            'live_tracking_url' => $this->live_tracking_url ?? $this->delivery?->live_tracking_url,
            'store'             => new StoreResource($this->whenLoaded('store')),
            'address'           => $this->address_snapshot,
            'items' => OrderItemResource::collection(
                $this->whenLoaded('items', function () {
                    $reviews = $this->whenLoaded('reviews');
                    
                    return $this->items->map(function ($item) use ($reviews) {
                        if ($reviews && !($reviews instanceof \Illuminate\Http\Resources\MissingValue)) {
                            $item->setRelation('review', $reviews->firstWhere('product_id', $item->product_id));
                        }
                        return $item;
                    });
                })
            ),
            'created_at'        => $this->created_at?->toIso8601String(),
        ];
    }
}