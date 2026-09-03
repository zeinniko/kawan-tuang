<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\MidtransPaymentService;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected MidtransPaymentService $paymentService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $status = $request->query('status');
        $orders = $this->orderService->getUserOrders($request->user(), $status);

        return response()->json([
            'data' => OrderResource::collection($orders),
        ]);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $order = Order::with(['store', 'address', 'items.product'])
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $order) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan atau akses ditolak.',
            ], 404);
        }

        return response()->json([
            'data' => new OrderResource($order),
        ]);
    }

    public function cancel(Request $request, Order $order): JsonResponse
    {
        $cancelledOrder = $this->orderService->cancelOrder($request->user(), $order);

        return response()->json([
            'message' => 'Pesanan berhasil dibatalkan.',
            'data' => new OrderResource($cancelledOrder),
        ]);
    }
}
