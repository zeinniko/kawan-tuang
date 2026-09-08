<?php

namespace App\Http\Resources\V1;

use App\Services\StorageService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $storageService = app(StorageService::class);

        return [
            'id'                  => $this->id,
            'code'                => $this->code,
            'banner'          => $this->banner ? $storageService->getUrl($this->banner, 'public') : null,
            'discount_type'       => $this->discount_type, // 'percentage' | 'fixed'
            'discount_value'      => (float) $this->discount_value,
            'min_order_amount'    => (float) ($this->min_order_amount ?? 0),
            'max_discount_amount' => $this->max_discount_amount ? (float) $this->max_discount_amount : null,
            'valid_from'          => $this->valid_from?->toIso8601String(),
            'valid_until'         => $this->valid_until?->toIso8601String(),
            'usage_limit'         => $this->usage_limit !== null ? (int) $this->usage_limit : null,
        ];
    }
}