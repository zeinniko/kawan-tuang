<?php

namespace App\Http\Resources\V1;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $items = $this->items ?? collect();

        // 1. Ambil store dari User atau Request/Cookie
        $user = $request->user();
        $store = $user?->store;

        $activeStoreId = $user?->store_id
            ?? $request->input('store_id')
            ?? $request->cookie('active_store_id');

        if (!$store && $activeStoreId) {
            $store = Store::find($activeStoreId);
        }

        $totalItems = $items->sum('quantity');

        // 2. Hitung total_price secara dinamis menggunakan harga markup toko aktif
        $totalPrice = $items->sum(function ($item) use ($store) {
            $basePrice = (float) ($item->unit_price ?? $item->product?->price ?? 0);
            $finalPrice = $store ? $store->calculateProductPrice($basePrice) : $basePrice;

            return $item->quantity * $finalPrice;
        });

        return [
            'id'          => $this->id,
            'store_id'    => $activeStoreId,
            'total_items' => (int) $totalItems,
            'total_price' => (float) $totalPrice,
            'items'       => CartItemResource::collection($items),
        ];
    }
}