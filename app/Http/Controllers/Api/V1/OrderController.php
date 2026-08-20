<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\MidtransPaymentService;

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

    public function store(Request $request): JsonResponse
    {
        // 1. Validasi input dari frontend
        $validated = $request->validate([
            'fulfillment_type'  => 'required|in:delivery,pickup',
            'shipping_cost'     => 'nullable|numeric|min:0',
            'store_id'          => 'required_if:fulfillment_type,pickup',
            'user_address_id'   => 'required_if:fulfillment_type,delivery',
            'courier_company'   => 'nullable|string',
            'courier_type'      => 'nullable|string',
            'payment_method'    => 'nullable|string',
            'notes'             => 'nullable|string',
            'voucher_code'      => 'nullable|string',
        ]);

        // Default value jika opsi pickup
        $validated['shipping_cost'] = $validated['fulfillment_type'] === 'pickup' ? 0 : ($validated['shipping_cost'] ?? 15000);
        $validated['payment_method'] = $validated['payment_method'] ?? 'midtrans';
        $validated['courier_company'] = $validated['courier_company'] ?? 'instant';
        $validated['courier_type'] = $validated['courier_type'] ?? 'gojek';

        // 2. Buat Order di Database
        $order = $this->orderService->processCheckout($request->user(), $validated);

        // 3. Generate Snap Token dari Midtrans
        $paymentData = $this->paymentService->createSnapToken($order);

        return response()->json([
            'message' => 'Order berhasil dibuat.',
            'order' => new OrderResource($order),
            'snap_token' => $paymentData['snap_token'],
            'redirect_url' => $paymentData['redirect_url'],
        ], 201);
    }


    public function show(Request $request, Order $order): JsonResponse
    {
        if ($order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        return response()->json([
            'data' => new OrderResource($order->load(['store', 'address', 'items'])),
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
