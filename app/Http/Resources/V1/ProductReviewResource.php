<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user_id,
                'full_name' => $this->user?->full_name,
            ],
            'product_id' => $this->product_id,
            'rating' => (int) $this->rating,
            'comment' => $this->comment,
            'photos' => collect($this->photos ?? [])->map(fn ($path) => Storage::disk('s3')->url($path)),
            'is_anonymous' => (bool) $this->is_anonymous,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}