<?php

namespace App\Http\Resources\V1;

use App\Services\StorageService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $storageService = app(StorageService::class);

        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'slug'           => $this->slug,
            'icon_url'       => $storageService->getUrl($this->icon_url, 'public'),
            'products_count' => $this->whenCounted('products'),
        ];
    }
}