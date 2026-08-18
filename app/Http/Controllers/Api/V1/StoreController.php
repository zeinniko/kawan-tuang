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
        $stores = $this->storeService->findNearestStores(
            (float) $request->latitude,
            (float) $request->longitude,
            (int) $limit
        );

        return response()->json([
            'message' => 'Daftar cabang terdekat berhasil didapatkan.',
            'data' => StoreResource::collection($stores),
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