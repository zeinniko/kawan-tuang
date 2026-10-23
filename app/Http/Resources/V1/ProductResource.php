<?php

namespace App\Http\Resources\V1;

use App\Services\StorageService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $storageService = app(StorageService::class);

        // 1. Ambil store_id dari Request (Query/User/Cookie)
        $activeStoreId = $request->input('store_id')
            ?? $request->user()?->store_id // <--- Disesuaikan menggunakan store_id
            ?? $request->cookie('active_store_id');

        $basePrice = (float) $this->price;
        $baseStrikePrice = $this->strike_price ? (float) $this->strike_price : null;

        $finalPrice = $basePrice;
        $finalStrikePrice = $baseStrikePrice;
        $activeStock = (int) $this->stock; // Fallback ke stok total

        // 2. Cari stok & data toko dari relasi storeStocks jika di-load
        if ($activeStoreId && $this->relationLoaded('storeStocks')) {
            $matchingStoreStock = $this->storeStocks->firstWhere('store_id', $activeStoreId);

            if ($matchingStoreStock) {
                // Ambil stok spesifik toko ini
                $activeStock = (int) $matchingStoreStock->stock;

                // Hitung harga final jika relasi store terhubung
                if ($matchingStoreStock->relationLoaded('store') && $matchingStoreStock->store) {
                    $store = $matchingStoreStock->store;
                    $finalPrice = $store->calculateProductPrice($basePrice);

                    if ($baseStrikePrice) {
                        $finalStrikePrice = $store->calculateProductPrice($baseStrikePrice);
                    }
                }
            }
        }

        // 3. Format Gambar
        $formattedImages = [];
        if ($this->relationLoaded('images') && $this->images->isNotEmpty()) {
            $formattedImages = $this->images->map(function ($img) use ($storageService) {
                return [
                    'id'            => $img->id,
                    'image_url'     => $storageService->getUrl($img->image_url, 'public'),
                    'is_primary'    => (bool) $img->is_primary,
                    'display_order' => (int) $img->display_order,
                ];
            })->toArray();
        }

        $primaryImgPath = $this->primaryImage->first()?->image_url
            ?? $this->images->first()?->image_url
            ?? null;

        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'slug'               => $this->slug,
            'sku'                => $this->sku,
            'price'              => $finalPrice,           // <--- Harga terhitung persentase toko
            'strike_price'       => $finalStrikePrice,     // <--- Coretan harga terhitung persentase toko
            'stock'              => $activeStock,          // <--- Stok spesifik toko aktif
            'abv'                => (float) $this->abv,
            'alcohol_percentage' => (float) $this->abv,
            'volume_ml'          => (int) $this->volume_ml,
            'is_cold_ready'      => (bool) $this->is_cold_ready,
            'is_active'          => (bool) $this->is_active,
            'thumbnail_url'      => $storageService->getUrl($primaryImgPath, 'public'),
            'images'             => $formattedImages,
            'description'        => $this->description,
            'category'           => new CategoryResource($this->whenLoaded('category')),
            'brand'              => new BrandResource($this->whenLoaded('brand')),
            'vibes'              => VibeResource::collection($this->whenLoaded('vibes')),
            'created_at'         => $this->created_at?->toIso8601String(),
            'store_stocks'       => $this->whenLoaded('storeStocks', function () {
                return $this->storeStocks->map(function ($item) {
                    return [
                        'store_id'   => $item->store_id,
                        'stock'      => (int) $item->stock,
                        'cold_stock' => (int) $item->cold_stock,
                    ];
                });
            }),
        ];
    }
}