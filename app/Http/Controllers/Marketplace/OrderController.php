<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Services\InternalApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $ordersResponse = InternalApiService::get('orders', [
            'status' => $request->query('status'),
        ]);

        return view('marketplace.orders', [
            'orders' => $ordersResponse['data'] ?? [],
        ]);
    }

    public function show($id): View
    {
        $orderResponse = InternalApiService::get("orders/{$id}");
        $orderData = $orderResponse['data'] ?? (isset($orderResponse['id']) ? $orderResponse : null);
        return view('marketplace.orders-detail', [
            'order' => $orderData,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $checkoutResponse = InternalApiService::post('checkout/process', [
            'fulfillment_type' => $request->input('fulfillment_type', 'delivery'),
            'shipping_cost'    => (float) $request->input('shipping_cost', 0),
            'store_id'         => (string) $request->input('store_id'),
            'user_address_id'  => (string) $request->input('user_address_id'),
            'courier_company'  => $request->input('courier_company'),
            'courier_type'     => $request->input('courier_type'),
            'payment_method'   => $request->input('payment_method', 'midtrans'),
            'voucher_code'     => $request->input('voucher_code'),
            'notes'            => $request->input('notes'),
        ]);

        if (isset($checkoutResponse['errors']) || empty($checkoutResponse['data']['id'])) {
            return response()->json([
                'message' => $checkoutResponse['message'] ?? 'Gagal membuat pesanan.',
                'errors'  => $checkoutResponse['errors'] ?? null,
            ], 422);
        }

        $orderId = $checkoutResponse['data']['id'];

        if (! $orderId) {
            return response()->json([
                'message' => 'Gagal mendapatkan ID Pesanan dari kalkulasi checkout.',
            ], 422);
        }
        $request->merge(['order_id' => $orderId]);
        // Ambil Snap Token
        $paymentResponse = InternalApiService::post('payments/snap-token');

        if (isset($paymentResponse['errors']) || empty($paymentResponse['data']['snap_token'])) {
            return response()->json([
                'message' => $paymentResponse['message'] ?? 'Gagal menerbitkan token pembayaran.',
                'errors'  => $paymentResponse['errors'] ?? null,
            ], 422);
        }

        $snapToken = $paymentResponse['data']['snap_token'];

        return response()->json([
            'status'     => 'success',
            'order_id'   => $orderId,
            'snap_token' => $snapToken,
            'message'    => 'Pesanan berhasil dibuat.',
        ], 201);
    }
}