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
        $user = $request->user();
    
        // 1. Cari ID toko yang sedang aktif dari User -> Request -> Cookie
        $activeStoreId = $user?->store_id
            ?? $request->input('store_id')
            ?? $request->cookie('active_store_id');
    
        $activeStore = null;
    
        // 2. Jika toko aktif sudah ada, ambil objek toko tersebut
        if ($activeStoreId) {
            $activeStore = Store::where('id', $activeStoreId)
                ->where('is_active', true)
                ->first();
        }
    
        // 3. Fallback: Jika belum ada toko aktif, cari 1 toko terdekat berdasarkan koordinat
        if (!$activeStore) {
            $lat = $request->input('latitude');
            $lng = $request->input('longitude');
    
            if ((is_null($lat) || is_null($lng)) && $user) {
                $primaryAddress = $user->addresses()->where('is_primary', true)->first() 
                    ?? $user->addresses()->first();
    
                if ($primaryAddress) {
                    $lat = $primaryAddress->latitude;
                    $lng = $primaryAddress->longitude;
                }
            }
    
            if (!is_null($lat) && !is_null($lng)) {
                $activeStore = $this->storeService->findNearestStores(
                    (float) $lat,
                    (float) $lng,
                    1
                )->first();
            }
        }
    
        // 4. Jika toko ditemukan, bungkus dalam collection berisi 1 item agar FE menerima format array
        if ($activeStore) {
            return response()->json([
                'message' => 'Daftar cabang terdekat berhasil didapatkan.',
                'data'    => StoreResource::collection(collect([$activeStore])),
            ]);
        }
    
        return response()->json([
            'message' => 'Toko aktif tidak ditemukan.',
            'data'    => [],
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