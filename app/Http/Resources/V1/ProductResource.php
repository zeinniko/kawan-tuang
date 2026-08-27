<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $primaryImg = $this->primaryImage->first()?->image_url
            ?? $this->images->first()?->image_url
            ?? null;

        $thumbnailPath = $this->thumbnail_url ?? $primaryImg;

        if ($thumbnailPath) {
            if (str_starts_with($thumbnailPath, 'http')) {
                $thumbnail = $thumbnailPath;
            } else {
                // Gunakan Storage disk 'public' lokal agar tidak mengecek konfigurasi AWS S3
                $thumbnail = Storage::disk('public')->url($thumbnailPath);
            }
        } else {
            $thumbnail = 'https://images.unsplash.com/photo-1614316650630-f2030d9980c6?auto=format&fit=crop&w=400&q=80';
        }

        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'slug'               => $this->slug,
            'sku'                => $this->sku,
            'price'              => (float) $this->price,
            'alcohol_percentage' => (float) $this->abv,
            'volume_ml'          => (int) $this->volume_ml,
            'thumbnail_url'      => $thumbnail,
            'description'        => $this->description,
            'is_featured'        => (bool) $this->is_featured,
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
