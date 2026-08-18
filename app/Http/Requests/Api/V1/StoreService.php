<?php

namespace App\Services;

use App\Models\Store;
use Illuminate\Database\Eloquent\Collection;

class StoreService
{
    public function getAllActiveStores(): Collection
    {
        return Store::where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();
    }

    public function findNearestStores(float $latitude, float $longitude, int $limit = 5): Collection
    {
        // Formula Haversine (Radius bumi = 6371 km)
        $haversine = "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))";

        return Store::where('is_active', true)
            ->selectRaw("*, {$haversine} AS distance", [$latitude, $longitude, $latitude])
            ->orderBy('distance', 'asc')
            ->limit($limit)
            ->get();
    }
}