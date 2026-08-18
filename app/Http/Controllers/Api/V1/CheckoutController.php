<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CheckoutPreviewRequest;
use App\Http\Requests\Api\V1\CheckoutProcessRequest;
use App\Http\Resources\V1\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class CheckoutController extends Controller
{
    public function __construct(protected OrderService $orderService) {}

    public function preview(CheckoutPreviewRequest $request): JsonResponse
    {
        $result = $this->orderService->previewCheckout(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Kalkulasi rincian checkout berhasil.',
            'data' => $result,
        ]);
    }

    public function process(CheckoutProcessRequest $request): JsonResponse
    {
        $order = $this->orderService->processCheckout(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Pesanan berhasil dibuat. Silakan lanjutkan pembayaran.',
            'data' => new OrderResource($order),
        ], 201);
    }
}