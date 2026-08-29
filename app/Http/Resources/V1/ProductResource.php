<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $formattedImages = [];

        if ($this->relationLoaded('images') && $this->images->isNotEmpty()) {
            $formattedImages = $this->images->map(function ($img) {
                return [
                    'id'            => $img->id,
                    'image_url'     => $img->image_url,
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
            'price'              => (float) $this->price,
            'strike_price'       => $this->strike_price ? (float) $this->strike_price : null,
            'stock'              => (int) $this->stock,
            'abv'                => (float) $this->abv,
            'alcohol_percentage' => (float) $this->abv,
            'volume_ml'          => (int) $this->volume_ml,
            'is_cold_ready'      => (bool) $this->is_cold_ready,
            'is_active'          => (bool) $this->is_active,
            'thumbnail_url'      => $primaryImgPath,
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