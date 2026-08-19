<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Services\InternalApiService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar riwayat pesanan & status tracking
     */
    public function index(Request $request)
    {
        $ordersResponse = InternalApiService::get('orders', [
            'status' => $request->query('status', 'active'),
        ]);

        return view('marketplace.orders', [
            'orders' => $ordersResponse['data'] ?? [],
        ]);
    }

    /**
     * Menampilkan detail pesanan berdasarkan ID
     */
    public function show($id)
    {
        $orderResponse = InternalApiService::get("orders/{$id}");

        return view('marketplace.orders-detail', [
            'order' => $orderResponse['data'] ?? null,
        ]);
    }

    /**
     * Memproses Checkout & Mengambil Snap Token Midtrans
     */
    public function store(Request $request)
    {
        // 1. Panggil API Checkout Process (CheckoutController@process)
        $checkoutResponse = InternalApiService::post('checkout/process', [
            'fulfillment_type' => $request->input('fulfillment_type', 'delivery'),
            'shipping_cost'    => $request->input('shipping_cost', 0),
            'store_id'         => $request->input('store_id'),
            'notes'            => $request->input('notes'),
        ]);

        if (!isset($checkoutResponse['data']['id'])) {
            return response()->json([
                'message' => $checkoutResponse['message'] ?? 'Gagal membuat pesanan.'
            ], 400);
        }

        $orderId = $checkoutResponse['data']['id'];

        // 2. Panggil API Payment untuk mendapatkan Snap Token Midtrans (PaymentController@generateSnapToken)
        $paymentResponse = InternalApiService::post('payments/snap-token', [
            'order_id' => $orderId,
        ]);

        $snapToken = $paymentResponse['data']['snap_token'] 
            ?? $paymentResponse['snap_token'] 
            ?? null;

        return response()->json([
            'order_id'   => $orderId,
            'snap_token' => $snapToken,
            'message'    => 'Pesanan berhasil dibuat.',
        ]);
    }
}