<?php

namespace App\Services;

use App\Models\Store;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        Log::info("=== [PERHITUNGAN JARAK TOKO TERDEKAT] ===", [
            'user_latitude'  => $latitude,
            'user_longitude' => $longitude,
            'limit'          => $limit,
        ]);

        // 1. Ambil kandidat awal menggunakan Haversine SQL
        $haversine = "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))";

        $stores = Store::where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw("*, {$haversine} AS distance", [$latitude, $longitude, $latitude])
            ->orderBy('distance', 'asc')
            ->limit(min($limit * 3, 15))
            ->get();

        if ($stores->isEmpty()) {
            Log::warning("[JARAK TOKO] Tidak ada toko aktif dengan koordinat valid.");
            return new Collection();
        }

        $googleApiKey = config('services.google.maps_api_key') ?? env('GOOGLE_MAPS_API_KEY');

        // 2. Jika Google Maps API Key tersedia, gunakan Google Distance Matrix API
        if (!empty($googleApiKey)) {
            Log::info("[JARAK TOKO] Provider Utama Digunakan: Google Maps Distance Matrix API");
            return $this->calculateViaGoogleMaps($stores, $latitude, $longitude, $googleApiKey, $limit);
        }

        // 3. Jika tidak ada API Key, gunakan OSRM (Open Source Routing Machine)
        Log::info("[JARAK TOKO] Provider Utama Digunakan: OSRM Route (Google API Key Tidak Ditemukan)");
        return $this->calculateViaOsrm($stores, $latitude, $longitude, $limit);
    }

    protected function calculateViaGoogleMaps(Collection $stores, float $userLat, float $userLng, string $apiKey, int $limit): Collection
    {
        try {
            $destinations = $stores->map(fn($s) => "{$s->latitude},{$s->longitude}")->implode('|');

            $response = Http::timeout(4)->get('https://maps.googleapis.com/maps/api/distancematrix/json', [
                'origins'      => "{$userLat},{$userLng}",
                'destinations' => $destinations,
                'key'          => $apiKey,
            ]);

            if ($response->successful() && $response->json('status') === 'OK') {
                $elements = $response->json('rows.0.elements') ?? [];

                foreach ($stores as $index => $store) {
                    if (isset($elements[$index]) && $elements[$index]['status'] === 'OK') {
                        $meters = $elements[$index]['distance']['value'];
                        $store->distance = round($meters / 1000, 2);
                        $store->distance_text = $elements[$index]['distance']['text'];
                        $store->distance_source = 'google_maps';

                        Log::info("[Google Maps API] Dari User ({$userLat}, {$userLng}) -> Toko '{$store->name}' ({$store->latitude}, {$store->longitude}) = {$store->distance_text} ({$store->distance} km)");
                    }
                }

                $sorted = $stores->sortBy('distance')->values()->take($limit);

                Log::info(">>> HASIL TERDEKAT [Google Maps]: Toko '{$sorted->first()->name}' dengan Jarak {$sorted->first()->distance_text}");
                return $sorted;
            }
        } catch (\Exception $e) {
            Log::warning('[Google Maps API Error]: ' . $e->getMessage());
        }

        // Fallback jika Google API bermasalah
        return $this->calculateViaOsrm($stores, $userLat, $userLng, $limit);
    }

    protected function calculateViaOsrm(Collection $stores, float $userLat, float $userLng, int $limit): Collection
    {
        try {
            $coordinates = ["{$userLng},{$userLat}"];
            foreach ($stores as $store) {
                $coordinates[] = "{$store->longitude},{$store->latitude}";
            }

            $coordString = implode(';', $coordinates);

            // Gunakan HTTPS dan tingkatkan timeout menjadi 5 detik
            $response = Http::timeout(5)->get("https://router.project-osrm.org/table/v1/driving/{$coordString}", [
                'sources' => '0',
            ]);

            if ($response->successful() && $response->json('code') === 'Ok') {
                $distances = $response->json('distances.0') ?? [];

                foreach ($stores as $index => $store) {
                    if (isset($distances[$index + 1])) {
                        $meters = $distances[$index + 1];
                        $km = round($meters / 1000, 2);
                        $store->distance = $km;
                        $store->distance_text = "{$km} km";
                        $store->distance_source = 'osrm_route';

                        Log::info("[OSRM Route] Dari User ({$userLat}, {$userLng}) -> Toko '{$store->name}' ({$store->latitude}, {$store->longitude}) = {$km} km");
                    }
                }

                $sorted = $stores->sortBy('distance')->values()->take($limit);
                Log::info(">>> HASIL TERDEKAT [OSRM]: Toko '{$sorted->first()->name}' dengan Jarak {$sorted->first()->distance_text}");
                return $sorted;
            }
        } catch (\Exception $e) {
            Log::warning('[OSRM API Error]: ' . $e->getMessage());
        }

        // Fallback ke Haversine jika OSRM timeout/gagal
        Log::info("[JARAK TOKO] Fallback ke Perhitungan Garis Lurus (Haversine)");
        return $stores->map(function ($store) {
            $km = round($store->distance, 2);
            $store->distance = $km;
            $store->distance_text = "{$km} km";
            $store->distance_source = 'haversine';
            return $store;
        })->sortBy('distance')->values()->take($limit);
    }
}
