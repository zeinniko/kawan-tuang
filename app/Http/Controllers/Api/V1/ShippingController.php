<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CheckShippingRateRequest;
use App\Http\Resources\V1\ShippingRateResource;
use App\Http\Resources\V1\ShippingTrackingResource;
use App\Models\Order;
use App\Models\Store;
use App\Models\UserAddress;
use App\Services\BiteshipService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function __construct(protected BiteshipService $biteshipService) {}

    public function getRates(CheckShippingRateRequest $request): JsonResponse
    {
        $store = Store::findOrFail($request->store_id);
        $address = UserAddress::where('id', $request->user_address_id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        try {
            $rates = $this->biteshipService->calculateRates($store, $address, $request->items);

            return response()->json([
                'message' => 'Tarif pengiriman berhasil diperhitungkan.',
                'data' => ShippingRateResource::collection($rates),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghitung tarif pengiriman.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function trackOrder(Request $request, Order $order): JsonResponse
    {
        if ($order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        try {
            $trackingData = $this->biteshipService->trackOrder($order);

            return response()->json([
                'data' => new ShippingTrackingResource($trackingData),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil informasi pelacakan.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}