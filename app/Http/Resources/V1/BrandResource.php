<?php

namespace App\Http\Resources\V1;

use App\Services\StorageService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $storageService = app(StorageService::class);

        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'slug'     => $this->slug,
            'logo_url' => $storageService->getUrl($this->logo_url, 'public'),
        ];
    }
}