<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Services\InternalApiService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $ordersResponse = InternalApiService::get('orders', [
            'status' => $request->query('status', 'active'),
        ]);

        return view('marketplace.orders', [
            'orders' => $ordersResponse['data'] ?? [],
        ]);
    }

    public function show($id)
    {
        $orderResponse = InternalApiService::get("orders/{$id}");

        return view('marketplace.orders-detail', [
            'order' => $orderResponse['data'] ?? null,
        ]);
    }

    public function store(Request $request)
    {
        // Teruskan seluruh payload request yang dibutuhkan oleh CheckoutProcessRequest
        $checkoutResponse = InternalApiService::post('checkout/process', [
            'fulfillment_type' => $request->input('fulfillment_type', 'delivery'),
            'shipping_cost'    => (float) $request->input('shipping_cost', 0),
            'store_id'         => (string) $request->input('store_id'),
            'user_address_id'  => (string) $request->input('user_address_id'),
            'courier_company'  => $request->input('courier_company'),
            'courier_type'     => $request->input('courier_type'),
            'payment_method'   => $request->input('payment_method'),
            'voucher_code'     => $request->input('voucher_code'),
            'notes'            => $request->input('notes'),
        ]);

        if (!isset($checkoutResponse['data']['id'])) {
            return response()->json([
                'message' => $checkoutResponse['message'] ?? 'Gagal membuat pesanan.',
                'errors'  => $checkoutResponse['errors'] ?? null,
            ], 400);
        }

        $orderId = $checkoutResponse['data']['id'];

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