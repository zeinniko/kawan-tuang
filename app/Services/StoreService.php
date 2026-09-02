<?php

namespace App\Services;

use App\Models\Store;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class StoreService
{
    public function getAllActiveStores(): Collection
    {
        return Store::where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Mencari toko terdekat menggunakan perhitungan garis lurus (Haversine Formula).
     */
    public function findNearestStores(float $latitude, float $longitude, int $limit = 5): Collection
    {
        Log::info("=== [PERHITUNGAN JARAK TOKO TERDEKAT (HAVERSINE)] ===", [
            'user_latitude'  => $latitude,
            'user_longitude' => $longitude,
            'limit'          => $limit,
        ]);

        // Rumus Haversine SQL + LEAST/GREATEST untuk mencegah error float precision pada acos()
        $haversine = "(6371 * acos(LEAST(1, GREATEST(-1, cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))))";

        $stores = Store::where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw("*, {$haversine} AS distance", [$latitude, $longitude, $latitude])
            ->orderBy('distance', 'asc')
            ->limit($limit)
            ->get();

        if ($stores->isEmpty()) {
            Log::warning("[JARAK TOKO] Tidak ada toko aktif dengan koordinat valid.");
            return new Collection();
        }

        // Format data jarak agar seragam
        $formattedStores = $stores->map(function ($store) {
            $km = round($store->distance, 2);
            $store->distance = $km;
            $store->distance_text = "{$km} km";
            $store->distance_source = 'haversine';
            return $store;
        });

        Log::info(">>> HASIL TERDEKAT: Toko '" . ($formattedStores->first()?->name ?? '-') . "' dengan Jarak " . ($formattedStores->first()?->distance_text ?? '-'));

        return $formattedStores;
    }
}