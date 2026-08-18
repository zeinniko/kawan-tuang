<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'title' => $this->title,
            'description' => $this->description,
            'discount_type' => $this->discount_type, // 'percentage' | 'fixed'
            'discount_value' => (float) $this->discount_value,
            'min_spend' => (float) $this->min_spend,
            'max_discount' => $this->max_discount ? (float) $this->max_discount : null,
            'expired_at' => $this->expired_at?->toIso8601String(),
            'is_active' => (bool) $this->is_active,
        ];
    }
}