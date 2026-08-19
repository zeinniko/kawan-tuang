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

        $thumbnail = $this->thumbnail_url 
            ? (str_starts_with($this->thumbnail_url, 'http') ? $this->thumbnail_url : Storage::disk('s3')->url($this->thumbnail_url))
            : ($primaryImg ? (str_starts_with($primaryImg, 'http') ? $primaryImg : Storage::disk('s3')->url($primaryImg)) : null);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'price' => (float) $this->price,
            'alcohol_percentage' => (float) $this->abv,
            'volume_ml' => (int) $this->volume_ml,
            'thumbnail_url' => $thumbnail ?? 'https://images.unsplash.com/photo-1614316650630-f2030d9980c6?auto=format&fit=crop&w=400&q=80',
            'description' => $this->description,
            'is_featured' => (bool) $this->is_featured,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'vibes' => VibeResource::collection($this->whenLoaded('vibes')),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}