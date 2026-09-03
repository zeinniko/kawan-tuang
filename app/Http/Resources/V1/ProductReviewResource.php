<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'rating'      => (int) $this->rating,
            'review_text' => $this->review_text,
            'photo_url'   => $this->photo_url,
            'created_at'  => $this->created_at?->toIso8601String(),
        ];
    }
}