<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'price' => (float) $this->price,
            'alcohol_percentage' => (float) $this->alcohol_percentage,
            'volume_ml' => (int) $this->volume_ml,
            'thumbnail_url' => $this->thumbnail_url ? Storage::disk('s3')->url($this->thumbnail_url) : null,
            'images' => collect($this->images ?? [])->map(fn ($path) => Storage::disk('s3')->url($path)),
            'description' => $this->description,
            'is_featured' => (bool) $this->is_featured,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'vibes' => VibeResource::collection($this->whenLoaded('vibes')),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}