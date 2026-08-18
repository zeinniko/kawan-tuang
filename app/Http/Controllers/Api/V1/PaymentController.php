<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CreatePaymentTokenRequest;
use App\Http\Resources\V1\OrderResource;
use App\Http\Resources\V1\PaymentResource;
use App\Models\Order;
use App\Services\MidtransPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(protected MidtransPaymentService $paymentService) {}

    public function generateSnapToken(CreatePaymentTokenRequest $request): JsonResponse
    {
        $order = Order::where('id', $request->order_id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        if ($order->payment_status === 'paid') {
            return response()->json(['message' => 'Pesanan ini sudah dibayar.'], 400);
        }

        $paymentData = $this->paymentService->createSnapToken($order);

        return response()->json([
            'message' => 'Snap Token berhasil diterbitkan.',
            'data' => new PaymentResource($paymentData),
        ]);
    }

    public function checkStatus(Request $request, Order $order): JsonResponse
    {
        if ($order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        return response()->json([
            'data' => new OrderResource($order->load(['store', 'address', 'items'])),
        ]);
    }
}