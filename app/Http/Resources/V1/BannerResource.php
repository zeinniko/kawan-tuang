<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BannerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image_url' => $this->image_url ? Storage::disk('s3')->url($this->image_url) : null,
            'target_type' => $this->target_type, // product, category, external_url
            'target_value' => $this->target_value,
        ];
    }
}