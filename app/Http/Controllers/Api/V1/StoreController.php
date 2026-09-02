<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\FindNearestStoreRequest;
use App\Http\Resources\V1\StoreResource;
use App\Models\Store;
use App\Services\StoreService;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    public function __construct(protected StoreService $storeService) {}

    public function index(): JsonResponse
    {
        $stores = $this->storeService->getAllActiveStores();

        return response()->json([
            'data' => StoreResource::collection($stores),
        ]);
    }

    public function nearest(FindNearestStoreRequest $request): JsonResponse
    {
        $limit = $request->input('limit', 5);
        $lat   = $request->input('latitude');
        $lng   = $request->input('longitude');

        // Jika lat & lng tidak dikirim, ambil dari alamat user yang sedang login
        if (is_null($lat) || is_null($lng)) {
            $user = $request->user();

            if ($user) {
                // Ambil alamat utama (is_primary = true) atau fallback ke alamat pertama
                $primaryAddress = $user->addresses()->where('is_primary', true)->first() 
                    ?? $user->addresses()->first();

                if ($primaryAddress) {
                    $lat = $primaryAddress->latitude;
                    $lng = $primaryAddress->longitude;
                }
            }
        }

        // Jika lat dan lng tetap tidak ada (Guest atau User belum set alamat)
        if (is_null($lat) || is_null($lng)) {
            return response()->json([
                'message' => 'Koordinat lokasi tidak ditemukan.',
                'data'    => [],
            ]);
        }

        $stores = $this->storeService->findNearestStores(
            (float) $lat,
            (float) $lng,
            (int) $limit
        );

        return response()->json([
            'message' => 'Daftar cabang terdekat berhasil didapatkan.',
            'data'    => StoreResource::collection($stores),
        ]);
    }

    public function show(Store $store): JsonResponse
    {
        if (! $store->is_active) {
            return response()->json(['message' => 'Cabang toko tidak aktif.'], 404);
        }

        return response()->json([
            'data' => new StoreResource($store),
        ]);
    }
}