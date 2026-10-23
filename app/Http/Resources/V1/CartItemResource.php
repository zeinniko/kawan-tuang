<?php

namespace App\Http\Resources\V1;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $basePrice = (float) ($this->unit_price ?? $this->product?->price ?? 0);

        // 1. Ambil store dari User atau Request/Cookie
        $user = $request->user();
        $store = $user?->store;

        if (!$store) {
            $storeId = $user?->store_id
                ?? $request->input('store_id')
                ?? $request->cookie('active_store_id');

            if ($storeId) {
                $store = Store::find($storeId);
            }
        }

        // 2. Hitung harga unit_price dinamis berdasarkan persentase toko
        $finalUnitPrice = $store ? $store->calculateProductPrice($basePrice) : $basePrice;

        $qty = (int) $this->quantity;
        $subtotal = $finalUnitPrice * $qty;

        return [
            'id'                 => $this->id,
            'product_id'         => $this->product_id,
            'product'            => new ProductResource($this->whenLoaded('product', $this->product)),
            'quantity'           => $qty,
            'unit_price'         => $finalUnitPrice,
            'subtotal'           => $subtotal,
            'temperature_option' => $this->temperature_option,
            'is_selected'        => (bool) $this->is_selected,
            'created_at'         => $this->created_at?->toIso8601String(),
        ];
    }
}